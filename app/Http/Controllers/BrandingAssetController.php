<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingAssetController extends Controller
{
    /**
     * Serve branding asset files from storage/app/public safely via Laravel.
     * Example: /branding/assets/branding/xxx.png
     */
    public function asset($path)
    {
        $path = ltrim($path, '/');

        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($path);

        // Return the file with correct headers and long caching for static branding assets
        // (branding images are content-addressed so a long max-age is safe)
        $response = response()->file($fullPath);
        // BinaryFileResponse doesn't have header() helper; use header bag instead
        $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        return $response;
    }
}
