<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyImage;
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
     * Remove a gallery image and its stored file. A property must keep at least one photo,
     * so the last one is refused (returns false).
     */
    public function deletePropertyImage(PropertyImage $image): bool
    {
        if (PropertyImage::where('property_id', $image->property_id)->count() <= 1) {
            return false;
        }

        $image->delete();

        // Seeded rows can share one file, so only remove it once nothing references it any more
        if (! PropertyImage::where('property_image_value', $image->property_image_value)->exists()) {
            Storage::delete($image->property_image_value);
        }

        return true;
    }
}
