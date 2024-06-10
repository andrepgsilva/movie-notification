<?php

return [
    'google_cloud_project' => env('GOOGLE_CLOUD_PROJECT', ''),
    'google_application_credentials' => env('GOOGLE_APPLICATION_CREDENTIALS', ''),
    'google_cloud_topic_prefix' => env('GOOGLE_CLOUD_TOPIC_PREFIX', ''),
    'google_cloud_subscription_prefix' => env('GOOGLE_CLOUD_SUBSCRIPTION_PREFIX', ''),
    'google_cloud_main_dead_letter_topic' => env('GOOGLE_CLOUD_MAIN_DEAD_LETTER_TOPIC', ''),
];
