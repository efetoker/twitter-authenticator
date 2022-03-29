<?php 

require_once "src.php";

$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
header("Location: ".$url);