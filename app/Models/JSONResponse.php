<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JSONResponse extends Model
{
    protected $data = [];
    protected $response = [
        "status" => false,
        "code" => 000,
        "message" => ""
    ];

    public function getAsJson(): string
    {
        return json_encode(
            [
                "data" => $this->data,
                "response" => $this->response
            ]
        );
    }

    public function getAsArray(): array
    {
        return [
            "data" => $this->data,
            "response" => $this->response
        ];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param array $response
     */
    public function setResponse(array $response): void
    {
        $this->response = $response;
    }
}
