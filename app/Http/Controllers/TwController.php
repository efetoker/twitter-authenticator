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

    public function like($oauth_token, $oauth_secret, $tweet_id){
        $connection = new TwitterOAuth(env('consumer_key', true), env('consumer_secret', true), $oauth_token, $oauth_secret);

        return $connection->post("favorites/create", ["id" => $tweet_id]);
    }

    public function unlike($oauth_token, $oauth_secret, $tweet_id){
        $connection = new TwitterOAuth(env('consumer_key', true), env('consumer_secret', true), $oauth_token, $oauth_secret);

        return $connection->post("favorites/destroy", ["id" => $tweet_id]);
    }

    public function retweet($oauth_token, $oauth_secret, $tweet_id){
        $connection = new TwitterOAuth(env('consumer_key', true), env('consumer_secret', true), $oauth_token, $oauth_secret);

        return $connection->post("statuses/retweet", ["id" => $tweet_id]);
    }

    public function unretweet($oauth_token, $oauth_secret, $tweet_id){
        $connection = new TwitterOAuth(env('consumer_key', true), env('consumer_secret', true), $oauth_token, $oauth_secret);

        return $connection->post("statuses/unretweet", ["id" => $tweet_id]);
    }

    public function reply($oauth_token, $oauth_secret, $tweet_id, $text){
        $connection = new TwitterOAuth(env('consumer_key', true), env('consumer_secret', true), $oauth_token, $oauth_secret);

        return $connection->post("statuses/update", ["in_reply_to_status_id" => $tweet_id, "status" => $text]);
    }

    public function get_tweet($oauth_token, $oauth_secret, $tweet_id){
        $connection = new TwitterOAuth(env('consumer_key', true), env('consumer_secret', true), $oauth_token, $oauth_secret);

        return $connection->get("statuses/show", ["id" => $tweet_id]);
    }
}
