<?php

require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'E3SmtQWPJ1xw6r9xfHbEAf0ru');
define('CONSUMER_SECRET', 'RnFU3Cej6l6XlidInNA9oGB0xffrc6ia8a87Vd87YZvUWs2mQY');
define('OAUTH_CALLBACK', 'http://twitter-authenticator.com/callback.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
