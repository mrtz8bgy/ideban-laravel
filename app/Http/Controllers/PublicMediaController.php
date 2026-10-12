<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class PublicMediaController extends Controller
{
    public function show(string $path)
    {
        abort_unless(preg_match('#\Amedia/[A-Za-z0-9_-]+/[A-Za-z0-9_-]+\.[A-Za-z0-9]{1,10}\z#', $path), 404);

        $disk = Storage::disk('public');
        abort_unless($disk->exists($path), 404);

        $filePath = $disk->path($path);
        abort_unless(is_file($filePath), 404);

        return response()->file($filePath, [
            'Cache-Control' => 'public, max-age=604800',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
