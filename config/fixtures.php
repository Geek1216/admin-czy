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
        'adcolony' => 'AdColony',
        'admob' => 'AdMob',
        'facebook' => 'Facebook',
        'mopub' => 'MoPub',
    ],
    'advertisement_network_to_types' => [
        'custom' => ['banner', 'interstitial'],
        'adcolony' => ['banner', 'interstitial'],
        'admob' => ['banner', 'interstitial', 'native'],
        'facebook' => ['banner', 'interstitial', 'native'],
        'mopub' => ['banner', 'interstitial', 'native'],
    ],
    'advertisement_types' => [
        'banner' => 'Banner',
        'interstitial' => 'Interstitial',
        'native' => 'Native',
    ],
    'api_key' => env('API_KEY'),
    'cache' => [
        'clips_count' => 60, // in minutes
        'comments_count' => 60, // in minutes
        'current_level' => 24 * 60, // in minutes
        'followers_count' => 60, // in minutes
        'likes_count' => 60, // in minutes
        'views_count' => 60, // in minutes
    ],
    'call_to_actions' => [
        'contact_us' => 'Contact us',
        'get_quote' => 'Get quote',
        'learn_more' => 'Learn more',
        'shop_now' => 'Shop now',
    ],
    'cdn_url' => env('CDN_URL'),
    'dashboard_statistics' => (bool) env('DASHBOARD_STATISTICS', false),
    'gifts_enabled' => (bool) env('GIFTS_ENABLED', false),
    'git_commit' => trim(@file_get_contents(base_path('COMMIT')) || 'n/a'),
    'install_done' => file_exists(storage_path('.installed')),
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
    'live_streaming_services' => [
        'agora' => 'Agora',
    ],
    'max_preview_size' => 256,
    'notification_schedule_clips' => [
        'latest' => 'Latest',
        'random' => 'Random',
    ],
    'otp_service' => env('OTP_SERVICE'),
    'payment_currency' => env('PAYMENT_CURRENCY', 'INR'),
    'payment_gateway' => env('PAYMENT_GATEWAY'),
    'payment_gateways' => [
        'play_store' => 'Play Store',
        'bitpay' => 'BitPay',
        'instamojo' => 'Instamojo',
        'paypal' => 'PayPal',
        'razorpay' => 'Razorpay',
        'stripe' => 'Stripe',
    ],
    'payment_statuses' => [
        'pending' => 'Pending',
        'successful' => 'Successful',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ],
    'ranking_algorithm' => env('RANKING_ALGORITHM', 'random'),
    'redemption_modes' => [
        'paypal' => 'PayPal',
        'upi' => 'UPI',
    ],
    'redemption_statuses' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],
    'referral_enabled' => (bool) env('REFERRAL_ENABLED', false),
    'referral_reward' => env('REFERRAL_REWARD', 0),
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
    'screening_service' => env('SCREENING_SERVICE'),
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
        'item' => [
            'image' => 5 * 1024,
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
