<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        if ($file = $request->file('file')) {
            $name = $file->store('uploads/'.date('Ymd'));

            return response()->json([
                'message' => __('messages.fileUploadSuccess'),
                'url' => Storage::disk()->url($name),
            ]);
        }

        return response()->json([
            'message' => 'No File',
        ], 400);
    }
}
