<?php

return [
    'advertisement_locations' => [
        'discover' => 'Discover',
        'grid' => 'Grid',
        'login' => 'Login',
        'news' => 'News',
        'notifications' => 'Notifications',
        'player' => 'Player',
        'search' => 'Search',
    ],
    'advertisement_location_to_types' => [
        'discover' => ['banner'],
        'grid' => ['banner'],
        'login' => ['banner'],
        'news' => ['banner', 'interstitial', 'native'],
        'notifications' => ['banner'],
        'player' => ['interstitial', 'native'],
        'search' => ['banner'],
    ],
    'advertisement_networks' => [
        'custom' => 'Custom',
        'admob' => 'AdMob',
    ],
    'advertisement_network_to_types' => [
        'custom' => ['banner', 'interstitial'],
        'admob' => ['banner', 'interstitial', 'native'],
    ],
    'advertisement_types' => [
        'banner' => 'Banner',
        'interstitial' => 'Interstitial',
        'native' => 'Native',
    ],
    'cdn_url' => env('CDN_URL'),
    'git_commit' => trim(file_get_contents(base_path('COMMIT'))),
    'languages' => [
        'hin' => 'Hindi',
        'eng' => 'English',
        'pan' => 'Punjabi',
        'bgc' => 'Haryanvi',
        'bho' => 'Bhojpuri',
        'ori' => 'Oriya',
        'ben' => 'Bengali',
        'guj' => 'Gujarati',
        'mar' => 'Marathi',
        'kan' => 'Kannada',
        'tam' => 'Tamil',
        'tel' => 'Telugu',
        'mal' => 'Malayalam',
    ],
    'link_types' => [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'linkedin' => 'LinkedIn',
        'snapchat' => 'Snapchat',
        'tiktok' => 'TikTok',
        'twitter' => 'Twitter',
        'youtube' => 'YouTube',
    ],
    'otp_service' => env('OTP_SERVICE'),
    'notification_schedule_clips' => [
        'latest' => 'Latest',
        'random' => 'Random',
    ],
    'purchase_code' => env('PURCHASE_CODE'),
    'report_reasons' => [
        'drugs' => 'Drugs',
        'fake_news' => 'Fake news',
        'fake_profile' => 'Fake profile',
        'harassment' => 'Harassment',
        'hateful' => 'Hateful',
        'ip_infringement' => 'IP infringement',
        'political_propaganda' => 'Political propaganda',
        'pornography' => 'Pornography',
        'spam' => 'Spam',
        'violence' => 'Violence',
        'weapons' => 'Weapons',
        'other' => 'Other',
    ],
    'report_statuses' => [
        'received' => 'Received',
        'processed' => 'Processed',
        'discarded' => 'Discarded',
    ],
    'upload_limits' => [ // in KBs
        'advertisement' => [
            'image' => 5 * 1024,
        ],
        'challenge' => [
            'image' => 5 * 1024,
        ],
        'clip' => [
            'video' => 50 * 1024,
            'screenshot' => 5 * 1024,
            'preview' => 10 * 1024,
        ],
        'promotion' => [
            'image' => 5 * 1024,
        ],
        'song' => [
            'audio' => 10 * 1024,
            'cover' => 1024,
        ],
        'sticker' => [
            'image' => 5 * 1024,
        ],
        'user' => [
            'photo' => 1024,
        ],
        'verification' => [
            'document' => 5 * 1024,
        ],
    ],
    'user_roles' => [
        'admin' => 'Administrator',
        'staff' => 'Staff',
    ],
    'verification_statuses' => [
        'pending' => 'Pending',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
    ],
];
