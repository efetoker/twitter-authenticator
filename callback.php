<?php 

require_once "src.php";

$access_token = $connection->oauth("oauth/access_token", ["oauth_token" => $_GET["oauth_token"], "oauth_verifier" => $_GET["oauth_verifier"]]);

echo "oauth_token: ". $access_token["oauth_token"] . "<br />";

echo "oauth_token_secret: ". $access_token["oauth_token_secret"] . "<br />";

echo "CONSUMER_KEY: ". CONSUMER_KEY . "<br />";