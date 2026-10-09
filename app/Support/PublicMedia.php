<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class PublicMedia
{
    public static function store(UploadedFile $file, string $directory): string
    {
        $name = Str::uuid().'.'.$file->extension();

        $path = $file->storeAs('media/'.$directory, $name, 'public');
        if (!$path) {
            throw new RuntimeException('Unable to store uploaded public media.');
        }

        return $path;
    }

    public static function url(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }

    public static function delete(?string $path): void
    {
        if ($path && strpos($path, 'media/') === 0 && strpos($path, '..') === false) {
            $disk = Storage::disk('public');
            if ($disk->exists($path) && !$disk->delete($path)) {
                throw new RuntimeException('Unable to delete stored public media.');
            }
        }
    }
}
