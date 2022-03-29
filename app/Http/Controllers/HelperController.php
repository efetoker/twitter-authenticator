<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class HelperController extends BaseController
{

    public function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * @throws \Exception
     */
    public function isValidForAuthentication($string): bool
    {
        if(empty($string)) {
            throw new \Exception('Empty string');
        }

        if(!$this->isJson($string)) {
            throw new \Exception("Invalid JSON");
        }

        if(
            !(isset(json_decode($string, true)["consumer_key"]) && json_decode($string, true)["consumer_key"] == env('consumer_key', true))
            ||
            !(isset(json_decode($string, true)["consumer_secret"]) && json_decode($string, true)["consumer_secret"] == env('consumer_secret', true))
        ){
            throw new \Exception("Invalid consumer key or consumer secret");
        }

        return true;
    }

}
