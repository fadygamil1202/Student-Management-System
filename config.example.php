<?php
// Copy this file to config.php and fill in your values. Never commit config.php.
return [
    // Full address of the site, used in NFC card links, e.g. 'https://cards.myschool.eg'.
    // Leave empty to detect it automatically (fine for local testing).
    'app_url'   => '',
    'app_key'   => 'change-me-to-a-long-random-string', // used to hash IPs in scan logs

    'db' => [
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'name'     => 'school_nfc',
        'user'     => 'school_nfc',   // XAMPP default: 'root'
        'password' => 'secret',       // XAMPP default: '' (empty)
    ],

    // AI insights (Claude API). Leave api_key empty to disable the feature.
    'ai' => [
        'api_key' => '',
        'model'   => 'claude-sonnet-5-5',
        'max_tokens' => 800,
    ],

    // Public emergency page: max views per IP per minute
    'public_rate_limit' => 20,
    // Login attempts per IP per 15 minutes
    'login_rate_limit' => 10,
];
