<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'analytics_id' => env('GOOGLE_ANALYTICS_ID', env('GA_MEASUREMENT_ID', 'G-VB4ZCKR8WB')),
        'tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID', env('GTM_ID', 'GT-M6X37ZJ4')),
    ],

    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID', env('FB_APP_ID')),
        'page_id' => env('FACEBOOK_PAGE_ID', '61580668026948'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Gemini AI API
    |--------------------------------------------------------------------------
    |
    | Used by AiBlogGeneratorService to generate blog post drafts and
    | decorative hero images. The key MUST only live in .env — never
    | commit it to version control.
    |
    */
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'text_model' => env('GEMINI_TEXT_MODEL', 'gemini-2.0-flash'),
        'image_model' => env('GEMINI_IMAGE_MODEL', 'imagen-3.0-generate-002'),
        'timeout' => (int) env('GEMINI_TIMEOUT', 60),
    ],

];
