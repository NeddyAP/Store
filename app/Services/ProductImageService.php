<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class ProductImageService
{
    /**
     * Handle the upload and processing of product images.
     *
     * @param UploadedFile $image
     * @param string $category
     * @return string The generated filename
     */
    public function handleImageUpload(UploadedFile $image, string $category): string
    {
        $filename = Carbon::now()->timestamp . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        // 1. Thumbnail Image (Canvas 500x800, Image 400x400)
        $destinationPath = public_path('asset/' . $category . '/thumbnail');
        $this->ensureDirectoryExists($destinationPath);

        $canvas = Image::canvas(500, 800);
        $img = Image::make($image->path());
        $img->resize(400, 400, function ($constraint) {
            $constraint->aspectRatio();
        });
        $canvas->insert($img, 'center')->save($destinationPath . '/' . $filename);

        // 2. Single Image (Resize 500x500)
        $destinationPath = public_path('asset/' . $category . '/single');
        $this->ensureDirectoryExists($destinationPath);

        $img = Image::make($image->path());
        $img->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $filename);

        // 3. 285 Image (Resize 285x400)
        $destinationPath = public_path('asset/' . $category . '/285');
        $this->ensureDirectoryExists($destinationPath);

        $img = Image::make($image->path());
        $img->resize(285, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $filename);

        // 4. Original Image (Move)
        $destinationPath = public_path('asset/' . $category . '/');
        // Move needs to be last because it moves the source file
        $image->move($destinationPath, $filename);

        return $filename;
    }

    /**
     * Delete all versions of the product image.
     *
     * @param string $category
     * @param string $filename
     * @return void
     */
    public function deleteImages(string $category, string $filename): void
    {
        File::delete(public_path('asset/' . $category . '/thumbnail/') . $filename);
        File::delete(public_path('asset/' . $category . '/single/') . $filename);
        File::delete(public_path('asset/' . $category . '/285/') . $filename);
        File::delete(public_path('asset/' . $category . '/') . $filename);
    }

    /**
     * Ensure the directory exists.
     *
     * @param string $path
     * @return void
     */
    private function ensureDirectoryExists(string $path): void
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
    }
}
