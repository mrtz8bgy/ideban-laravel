<?php

return [
    // PHP's upload_max_filesize and post_max_size must also allow this size.
    'public_video_max_mb' => (int) env('PUBLIC_VIDEO_MAX_MB', 256),
];
