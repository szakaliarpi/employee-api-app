<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;

class SendIdempotentRequestAction
{
    public function execute($token, $url, $username, $password)
    {
        $secret = hash_hmac('sha256', $username, $password);

        for ($i = 0; $i < 10; $i++) {
            $uuid = Uuid::uuid4()->toString();
            $response = $this->sendRequest($url, $uuid, $token, $username,$password, $secret);
            $this->storeInJSon($response);
        }
    }

    private function sendRequest($url, $uuid, $token, $username,$password, $secret)
    {
        $form = [
            'token' => $token,
            'secret' => $secret,
            'uuid' => $uuid
        ];

        $response = Http::withBasicAuth($username, $password)
            ->asJson()
            ->post($url . '?action=sendIdempotentRequest', $form);

        $response_body = json_decode($response->body());

        if ($response_body === null)
            return $this->sendRequest($url, $uuid, $token, $username,$password, $secret);
        else {
            $response_body->uuid = $uuid;
            return $response_body;
        }
    }

    private function storeInJson($response)
    {
        $filePath = 'data.json';
        $jsonContents = Storage::get($filePath);
        $data = json_decode($jsonContents, true);

        $newData = [
            'resultCode' => $response->resultCode,
            'resultMsg' => $response->resultMsg,
            'try_cnt' => $response->try_cnt,
            'uuid' => $response->uuid
        ];

        $data[] = $newData;
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        Storage::put('data.json', $jsonData);
    }
}
