<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Laravel\Lumen\Routing\Controller;

class TwController extends Controller
{
    public function verify($oauth_token, $oauth_secret){
        $connection = new TwitterOAuth(env('consumer_key', true), env('consumer_secret', true), $oauth_token, $oauth_secret);

        return $connection->get("account/verify_credentials");
    }
}
