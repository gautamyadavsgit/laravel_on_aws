<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class Media
{
    /**
     * Storage disk used for uploaded media.
     * "local" maps to the "public" disk so files are web accessible.
     */
    public static function disk(): string
    {
        $default = config('filesystems.default', 'public');

        return $default === 'local' ? 'public' : $default;
    }

    /**
     * Public URL for a stored media path on the active disk.
     */
    public static function url(?string $path): string
    {
        $path = ltrim((string) $path, '/');
        $disk = static::disk();

        // Local disks are served through the public/storage symlink.
        if (config("filesystems.disks.{$disk}.driver") === 'local') {
            return asset('storage/'.$path);
        }

        return Storage::disk($disk)->url($path);
    }
}
