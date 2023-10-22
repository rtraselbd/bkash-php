<?php

use RT\bKash\bKash;

require_once 'vendor/autoload.php';

$success_url = 'https://localhost/success.php'; // Replace with your success URL
$brand_name = 'UddoktaPay'; // // Add your brand name

$credential = [
    'username'      => 'your-bKash-username',
    'password'      => 'your-bKash-password',
    'app_key'       => 'your-app-key',
    'app_secret'    => 'your-app-secret',
];
$bKash = new bKash($credential);
