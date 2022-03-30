<?php

namespace App\Http\Controllers;

use App\Models\JSONResponse;
use Laravel\Lumen\Routing\Controller;

class DbController extends Controller
{
    public function addAccount(string $json): JSONResponse{
        $resp = new JSONResponse();
        $data = json_decode($json, true);

        try{
            $res = app('db')->insert("INSERT INTO secrets (oauth_token, oauth_token_secret) VALUES (?, ?)", [
                $data['oauth_token'],
                $data['oauth_token_secret']
            ]);

            if ($res) {
                $resp->setResponse(["status" => true, "code" => 200, "message" => "Successfully added new account to DB."]);
            }else{
                $resp->setResponse(["status" => false, "code" => 500, "message" => "Failed to add new account to DB."]);
            }
        } catch (\Exception $e) {
            $resp->setResponse(["status" => false, "code" => 500, "message" => $e->getMessage()]);
        }

        return $resp;
    }

    public function getAccounts(): JSONResponse{
        $resp = new JSONResponse();

        try{
            $res = app('db')->select("SELECT * FROM secrets");

            if ($res) {
                $resp->setData(array_map(function($item){
                    $tw = new TwController();

                    return [
                        "oauth_token" => $item->oauth_token,
                        "oauth_token_secret" => $item->oauth_token_secret,
                        "id" => $item->id
                    ];
                }, $res));
                $resp->setResponse(["status" => true, "code" => 200, "message" => "Successfully retrieved accounts from DB."]);
            }else{
                $resp->setResponse(["status" => false, "code" => 200, "message" => "No accounts found in DB."]);
            }
        } catch (\Exception $e) {
            $resp->setResponse(["status" => false, "code" => 500, "message" => $e->getMessage()]);
        }

        return $resp;
    }

    public function deleteAccount(int $id): JSONResponse{
        $resp = new JSONResponse();

        try{
            $res = app('db')->delete("DELETE FROM secrets WHERE id = ?", [
                $id
            ]);

            if ($res) {
                $resp->setResponse(["status" => true, "code" => 200, "message" => "Successfully deleted account from DB."]);
            }else{
                $resp->setResponse(["status" => false, "code" => 500, "message" => "Failed to delete account from DB."]);
            }
        } catch (\Exception $e) {
            $resp->setResponse(["status" => false, "code" => 500, "message" => $e->getMessage()]);
        }

        return $resp;
    }

    public function getAccount(int $id): JSONResponse{
        $resp = new JSONResponse();

        try{
            $res = app('db')->select("SELECT * FROM secrets WHERE id = ?", [
                $id
            ]);

            if ($res) {
                $resp->setData($res);
                $resp->setResponse(["status" => true, "code" => 200, "message" => "Successfully retrieved account from DB."]);
            }else{
                $resp->setResponse(["status" => false, "code" => 200, "message" => "No account found in DB."]);
            }
        } catch (\Exception $e) {
            $resp->setResponse(["status" => false, "code" => 500, "message" => $e->getMessage()]);
        }

        return $resp;
    }
}
