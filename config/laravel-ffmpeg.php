<?php

return [
    'default_disk' => 'local',

    'ffmpeg' => [
        'binaries' => env('FFMPEG_BINARIES', 'ffmpeg'),
        // 'binaries' => exec('which ffmpeg'),
        'threads' => 12,
    ],

    'ffprobe' => [
        'binaries' => env('FFPROBE_BINARIES', 'ffprobe'),
        // 'binaries' => exec('which ffprobe'),
    ],

    'timeout' => 3600,
];
