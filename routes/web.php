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
        header("Location: ".$url);
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

$router->get('/account/{id}/verify', function (Request $req, $id) use ($router) {
    $res = new JSONResponse();
    $db = new DbController();
    $tw = new TwController();

    $account = (array)$db->getAccount($id)->getData()[0];
    $data = (array)$tw->verify($account["oauth_token"], $account["oauth_token_secret"]);

    if(isset($data["id"])){
        $res->setResponse(["status" => true, "code" => 200, "message" => "Account verified", "data" => $data]);
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
