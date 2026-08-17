<?php

namespace App\Services;

use App\Models\PropertyModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PropertyMediaService
{
    /**
     * Get default storage disk name
     */
    protected function disk(): string
    {
        return config('filesystems.default', 'public') === 'local' ? 'public' : config('filesystems.default', 'public');
    }

    /**
     * Upload and attach property gallery images
     *
     * @param  UploadedFile[]  $files
     */
    public function uploadPropertyImages(PropertyModel $property, array $files): void
    {
        $diskName = $this->disk();
        $records = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = Storage::disk($diskName)->putFile('property_images', $file);
                if ($path) {
                    $records[] = [
                        'property_image_key' => 'property_image',
                        'property_image_value' => $path,
                    ];
                }
            }
        }

        if (! empty($records)) {
            $property->propertyImage()->createMany($records);
        }
    }

    /**
     * Upload and sync floorplan images
     */
    public function uploadFloorplans(PropertyModel $property, array $fileInputs, array $filteredData): void
    {
        $diskName = $this->disk();
        $propertyFloorplan = $property->propertyFloorplan;
        $newData = [];

        foreach ($filteredData as $key => $value) {
            if (isset($fileInputs[$key]) && $fileInputs[$key] instanceof UploadedFile) {
                $path = Storage::disk($diskName)->putFile('floorplan_images', $fileInputs[$key]);
                if ($path) {
                    $newData[] = ['key' => $key, 'value' => $path];
                }
            }
        }

        if (! $propertyFloorplan->isEmpty()) {
            foreach ($propertyFloorplan as $model) {
                if (array_key_exists($model->key, $filteredData)) {
                    Storage::disk($diskName)->delete($model->value);
                    $model->delete();
                }
            }
        }

        if (! empty($newData)) {
            $property->propertyFloorplan()->createMany($newData);
        }
    }

    /**
     * Upload and sync property documents
     */
    public function uploadDocuments(PropertyModel $property, array $fileInputs, array $filteredData): void
    {
        $diskName = $this->disk();
        $propertyDocumentModel = $property->propertyDocumentModel;
        $newData = [];

        foreach ($filteredData as $key => $value) {
            if (isset($fileInputs[$key]) && $fileInputs[$key] instanceof UploadedFile) {
                $path = Storage::disk($diskName)->putFile('property_documents', $fileInputs[$key]);
                if ($path) {
                    $newData[] = ['document_key' => $key, 'document_value' => $path];
                }
            }
        }

        if (! $propertyDocumentModel->isEmpty()) {
            foreach ($propertyDocumentModel as $model) {
                if (array_key_exists($model->document_key, $filteredData)) {
                    Storage::disk($diskName)->delete($model->document_value);
                    $model->delete();
                }
            }
        }

        if (! empty($newData)) {
            $property->propertyDocumentModel()->createMany($newData);
        }
    }

    /**
     * Delete a stored file from storage disk
     */
    public function deleteFile(string $path, ?string $disk = null): bool
    {
        $diskName = $disk ?? $this->disk();

        return Storage::disk($diskName)->delete($path);
    }
}
