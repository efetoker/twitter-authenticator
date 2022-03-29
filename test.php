<?php 

require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth("", "", "", "");
echo print_r($connection->get("account/verify_credentials"), true);
$connection2 = new TwitterOAuth("", "", "", "");

echo print_r($connection->get("account/verify_credentials"), true);

$connection->post("statuses/update", ["status" => "."]);
$connection2->post("statuses/update", ["status" => "."]);