<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Controllers\DbController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\TwController;
use App\Models\JSONResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Router;

/** @var Router $router */

$router->get('/', function () use ($router) {
    return "Hello world!";
});

$router->get('/start-auth', function () use ($router) {
    $res = new JSONResponse();

    try{
        $connection = new TwitterOAuth(env('consumer_key', true), env('consumer_secret', true));
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => env('oauth_callback', true)));
        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

        return redirect()->to($url);

        echo "If you are not redirected, please click <a href='$url'>here</a>";
    } catch (Exception $e) {
        $res->setResponse(["status" => false, "code" => 500, "message" => $e->getMessage()]);
    }

    return $res;
});

$router->get('/callback', function (Request $req) use ($router) {
    $db = new DbController();

    $connection = new TwitterOAuth(env('consumer_key', true), env('consumer_secret', true));
    $access_token = $connection->oauth("oauth/access_token", ["oauth_token" => $req->get("oauth_token"), "oauth_verifier" => $req->get("oauth_verifier")]);

    if(isset($access_token["oauth_token"]) && isset($access_token["oauth_token_secret"])){
        $res = $db->addAccount(json_encode($access_token));

        if($res->getResponse()["status"]){
            echo "Account successfully added! <br /><br />Redirecting in 5 secs...";
            header( "refresh:5;url=https://shill-a-tweet.herokuapp.com/" );
        }else{
            echo "Something went wrong! <br /> (" . $res->getResponse()["message"] . ") <br /><br />Redirecting in 5 secs...";
            header( "refresh:5;url=https://shill-a-tweet.herokuapp.com/" );
        }
    }
});

$router->get('/accounts', function () use ($router) {
    $db = new DbController();
    return response()->json($db->getAccounts()->getAsArray());
});

$router->get('/tweet/{id}/{token}/{secret}', function (Request $req, $id, $token, $secret) use ($router) {
    $res = new JSONResponse();
    $tw = new TwController();

    $tweet = (array)$tw->get_tweet($token, $secret, $id);

    if(isset($tweet["id"])){
        $res->setData($tweet);
        $res->setResponse(["status" => true, "code" => 200, "message" => "Account verified"]);
    }else{
        $errorMessage = null;

        if(isset($tweet["errors"]) && isset($tweet["errors"][0])){
            $arr = (array)$tweet["errors"][0];
            $errorMessage = $arr["message"];
        }else{
            $errorMessage = "Unknown error";
        }

        $res->setResponse(["status" => false, "code" => 500, "message" => $errorMessage]);
    }

    return response()->json($res->getAsArray());
});

$router->get('/account/{id}/verify', function (Request $req, $id) use ($router) {
    $res = new JSONResponse();
    $db = new DbController();
    $tw = new TwController();

    $account = (array)$db->getAccount($id)->getData()[0];
    $data = (array)$tw->verify($account["oauth_token"], $account["oauth_token_secret"]);

    if(isset($data["id"])){
        $res->setData($data);
        $res->setResponse(["status" => true, "code" => 200, "message" => "Account verified"]);
    }else{
        $res->setResponse(["status" => false, "code" => 500, "message" => $data["errors"] && $data["errors"][0] ? $data["errors"][0]["message"] : "Unknown error"]);
    }

    return response()->json($res->getAsArray());
});

$router->post('/account', function (Request $req) use ($router) {
    $helper = new HelperController();
    $db = new DbController();
    $json = new JSONResponse();

    if(!$helper->isValidForAuthentication($req->getContent())) {
        $json->setResponse(["status" => true, "code" => 400]);
    }else{
        $json = $db->addAccount($req->getContent());
    }

    return response()->json($json->getAsArray());
});

$router->post('/like/{tweetId}', function (Request $req, $tweetId) use ($router) {
    $tw = new TwController();
    $json = new JSONResponse();

    $data = (array)$tw->like(json_decode($req->getContent(), true)["oauth_token"], json_decode($req->getContent(), true)["oauth_token_secret"], $tweetId);

    if(isset($data["created_at"])){
        $json->setData($data);
        $json->setResponse(["status" => true, "code" => 200, "message" => "Tweet liked"]);
    }else{
        $errorMessage = null;

        if(isset($data["errors"]) && isset($data["errors"][0])){
            $arr = (array)$data["errors"][0];
            $errorMessage = $arr["message"];
        }else{
            $errorMessage = "Unknown error";
        }

        $json->setResponse(["status" => false, "code" => 500, "message" => $errorMessage]);
    }

    return response()->json($json->getAsArray());
});

$router->post('/unlike/{tweetId}', function (Request $req, $tweetId) use ($router) {
    $tw = new TwController();
    $json = new JSONResponse();

    $data = (array)$tw->unlike(json_decode($req->getContent(), true)["oauth_token"], json_decode($req->getContent(), true)["oauth_token_secret"], $tweetId);

    if(isset($data["created_at"])){
        $json->setData($data);
        $json->setResponse(["status" => true, "code" => 200, "message" => "Tweet liked"]);
    }else{
        $errorMessage = null;

        if(isset($data["errors"]) && isset($data["errors"][0])){
            $arr = (array)$data["errors"][0];
            $errorMessage = $arr["message"];
        }else{
            $errorMessage = "Unknown error";
        }

        $json->setResponse(["status" => false, "code" => 500, "message" => $errorMessage]);
    }

    return response()->json($json->getAsArray());
});

$router->post('/retweet/{tweetId}', function (Request $req, $tweetId) use ($router) {
    $tw = new TwController();
    $json = new JSONResponse();

    $data = (array)$tw->retweet(json_decode($req->getContent(), true)["oauth_token"], json_decode($req->getContent(), true)["oauth_token_secret"], $tweetId);

    if(isset($data["created_at"])){
        $json->setData($data);
        $json->setResponse(["status" => true, "code" => 200, "message" => "Tweet liked"]);
    }else{
        $errorMessage = null;

        if(isset($data["errors"]) && isset($data["errors"][0])){
            $arr = (array)$data["errors"][0];
            $errorMessage = $arr["message"];
        }else{
            $errorMessage = "Unknown error";
        }

        $json->setResponse(["status" => false, "code" => 500, "message" => $errorMessage]);
    }

    return response()->json($json->getAsArray());
});

$router->post('/unretweet/{tweetId}', function (Request $req, $tweetId) use ($router) {
    $tw = new TwController();
    $json = new JSONResponse();

    $data = (array)$tw->unretweet(json_decode($req->getContent(), true)["oauth_token"], json_decode($req->getContent(), true)["oauth_token_secret"], $tweetId);

    if(isset($data["created_at"])){
        $json->setData($data);
        $json->setResponse(["status" => true, "code" => 200, "message" => "Tweet retweeted"]);
    }else{
        $errorMessage = null;

        if(isset($data["errors"]) && isset($data["errors"][0])){
            $arr = (array)$data["errors"][0];
            $errorMessage = $arr["message"];
        }else{
            $errorMessage = "Unknown error";
        }

        $json->setResponse(["status" => false, "code" => 500, "message" => $errorMessage]);
    }

    return response()->json($json->getAsArray());
});

$router->post('/reply/{tweetId}', function (Request $req, $tweetId) use ($router) {
    $tw = new TwController();
    $json = new JSONResponse();

    try{
        $tweet = (array)$tw->get_tweet(json_decode($req->getContent(), true)["oauth_token"], json_decode($req->getContent(), true)["oauth_token_secret"], $tweetId);

        if(isset($tweet["created_at"])){
            $user = (array)$tweet["user"];
            $screen_name = $user["screen_name"];
            $data = (array)$tw->reply(json_decode($req->getContent(), true)["oauth_token"], json_decode($req->getContent(), true)["oauth_token_secret"], $tweetId, "@" . $screen_name . " " .json_decode($req->getContent(), true)["tweet"]);

            if(isset($data["created_at"])){
                $json->setData($data);
                $json->setResponse(["status" => true, "code" => 200, "message" => "Replied successfully."]);
            }else{
                $errorMessage = null;

                if(isset($data["errors"]) && isset($data["errors"][0])){
                    $arr = (array)$data["errors"][0];
                    $errorMessage = $arr["message"];
                }else{
                    $errorMessage = "Unknown error";
                }

                $json->setResponse(["status" => false, "code" => 500, "message" => $errorMessage]);
            }
        }else{
            $errorMessage = null;

            if(isset($tweet["errors"]) && isset($tweet["errors"][0])){
                $arr = (array)$tweet["errors"][0];
                $errorMessage = $arr["message"];
            }else{
                $errorMessage = "Unknown error";
            }

            $json->setResponse(["status" => false, "code" => 500, "message" => $errorMessage]);
        }
    } catch (\Exception $e){
        $json->setResponse(["status" => false, "code" => 500, "message" => $e->getMessage()]);
    }

    return response()->json($json->getAsArray());
});

$router->delete('/account/{id}', function (Request $req, $id) use ($router) {
    $helper = new HelperController();
    $db = new DbController();
    $json = new JSONResponse();

    if(!$id){
        $json->setResponse(["status" => false, "code" => 400]);
    }else{
        $json = $db->deleteAccount($id);
    }

    return response()->json($json->getAsArray());
});
