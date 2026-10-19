<?php

return [
    'platform_commission_rate' => (float) env('PLATFORM_COMMISSION_RATE', 15),
    'partner_share_rate' => (float) env('PARTNER_SHARE_RATE', 85),
    'order_auto_assign_timeout' => (int) env('ORDER_AUTO_ASSIGN_TIMEOUT_SECONDS', 60),
    'order_partner_response_timeout' => (int) env('ORDER_PARTNER_RESPONSE_TIMEOUT_SECONDS', 120),
    'distance_adjustment_factor' => (float) env('DISTANCE_ADJUSTMENT_FACTOR', 1.3),
    'max_partner_search_radius_km' => (float) env('MAX_PARTNER_SEARCH_RADIUS_KM', 25),
    'max_photo_upload_size_kb' => (int) env('MAX_PHOTO_UPLOAD_SIZE_KB', 5120),
    'max_photos_per_order' => (int) env('MAX_PHOTOS_PER_ORDER', 5),
];
