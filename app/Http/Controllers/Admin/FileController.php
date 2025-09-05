<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\UploadRequest;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(UploadRequest $request): JsonResponse
    {
        $file = $request->file('file');

        $disk = config('upload.disk', 'public');
        $baseDir = trim(config('upload.dir', 'uploads'), '/');
        $dir = $baseDir . '/' . date('Ymd');

        $path = $file->storePublicly($dir, $disk);

        return response()->json([
            'message' => __('messages.fileUploadSuccess'),
            'url' => Storage::disk($disk)->url($path),
            'path' => $path,
        ]);
    }
}
