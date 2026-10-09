<?php

return [
    // PHP's upload_max_filesize and post_max_size must also allow this size.
    'max_video_mb' => (int) env('ACADEMY_MAX_VIDEO_MB', 1024),
    'video_disk' => 'local',
    'video_dir' => 'academy/videos',
];
