<?php

return [
    // Filesystem disk to store uploads
    'disk' => env('UPLOAD_DISK', 'public'),

    // Base directory under the disk
    'dir' => env('UPLOAD_DIR', 'uploads'),

    // Max file size in kilobytes
    'max_kb' => env('UPLOAD_MAX_KB', 2048),

    // Allowed extensions for mimes rule (comma-separated)
    'mimes' => env('UPLOAD_MIMES', 'jpg,jpeg,png,webp,gif,pdf'),
];

