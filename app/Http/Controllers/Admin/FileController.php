<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(UploadRequest $request): JsonResponse
    {
        $file = $request->file('file');

        $path = $file->storePublicly('/', 'temp');

        return response()->json([
            'message' => __('messages.fileUploadSuccess'),
            'url' => Storage::disk('temp')->url($path),
            'path' => $path,
        ]);
    }
}
