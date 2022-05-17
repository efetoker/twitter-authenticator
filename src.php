<?php

require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'ZeBMFN5SOmgY7Ehou6JT6SfPR');
define('CONSUMER_SECRET', 'kl7eGW7RcCM5MurrQRmlI0wErQLlUIEbQmjMVWcpc3LPLJ4yV6');
define('OAUTH_CALLBACK', 'http://twitter-authenticator.com/callback.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
