<?php 

require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth("Aw4Z6ZBmTYB6J2JXCzgezZKVk", "azPwhG9g56NQBA7cNJRkg3a2oEJ8Gq7DOnk2W8SUohrNZrE8Qk", "88682260-U15Rrm4cNtVR2fqIVkTaauxINXaNybfE7C1sLpb9l", "EMV203qXMSrKwYsq8pynfKvRYzmCYPgYn8NdJluztf8Xp");
echo print_r($connection->get("account/verify_credentials"), true);

$connection2 = new TwitterOAuth("Aw4Z6ZBmTYB6J2JXCzgezZKVk", "azPwhG9g56NQBA7cNJRkg3a2oEJ8Gq7DOnk2W8SUohrNZrE8Qk", "1461691227863191554-E0pcOOGZ5hvYWDmlYM8GkdNIRV2KSh", "zxAJ1tFJ1Zfz9HosL3OpwzdRueA2WRgbxSXatnfmKdWQm");
echo print_r($connection->get("account/verify_credentials"), true);

$connection->post("statuses/update", ["status" => "."]);
$connection2->post("statuses/update", ["status" => "."]);