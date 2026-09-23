<?php

namespace App\Services;

use App\Models\PropertyModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PropertyMediaService
{
    /**
     * Upload and attach property gallery images
     *
     * @param  UploadedFile[]  $files
     */
    public function uploadPropertyImages(PropertyModel $property, array $files): void
    {
        $records = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = Storage::putFile('property_images', $file);
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
     * Upload and sync property documents
     */
    public function uploadDocuments(PropertyModel $property, array $fileInputs, array $filteredData): void
    {
        $propertyDocumentModel = $property->propertyDocumentModel;
        $newData = [];

        foreach ($filteredData as $key => $value) {
            if (isset($fileInputs[$key]) && $fileInputs[$key] instanceof UploadedFile) {
                $path = Storage::putFile('property_documents', $fileInputs[$key]);
                if ($path) {
                    $newData[] = ['document_key' => $key, 'document_value' => $path];
                }
            }
        }

        if (! $propertyDocumentModel->isEmpty()) {
            foreach ($propertyDocumentModel as $model) {
                if (array_key_exists($model->document_key, $filteredData)) {
                    Storage::delete($model->document_value);
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
        return Storage::disk($disk)->delete($path);
    }
}
