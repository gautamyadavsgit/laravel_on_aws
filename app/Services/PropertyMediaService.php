<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PropertyMediaService
{
    /**
     * Upload and attach property gallery images
     *
     * @param  UploadedFile[]  $files
     */
    public function uploadPropertyImages(Property $property, array $files): void
    {
        $records = [];

        foreach ($files as $file) {
            $path = Storage::putFile('property_images', $file);
            if ($path) {
                $records[] = [
                    'property_image_key' => 'property_image',
                    'property_image_value' => $path,
                ];
            }
        }

        if (! empty($records)) {
            $property->images()->createMany($records);
        }
    }

    /**
     * Upload and sync property documents
     */
    public function uploadDocuments(Property $property, array $fileInputs, array $filteredData): void
    {
        $propertyDocumentModel = $property->documents;
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
            $property->documents()->createMany($newData);
        }
    }
}
