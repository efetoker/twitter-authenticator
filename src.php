<?php

require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'Aw4Z6ZBmTYB6J2JXCzgezZKVk');
define('CONSUMER_SECRET', 'azPwhG9g56NQBA7cNJRkg3a2oEJ8Gq7DOnk2W8SUohrNZrE8Qk');
define('OAUTH_CALLBACK', 'http://twitter-authenticator.com/callback.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);