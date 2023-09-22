<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class SendJsonRequestAction
{
    public function execute($token, $url, $username, $password)
    {
        $secret = hash_hmac('sha256', $username, $password);
        $form =  [
            'token' => $token,
            'secret' => $secret
        ];

        $response = Http::withBasicAuth($username, $password)
            ->post($url . '?action=sendJsonRequest', $form);

        return $response->body();
    }
}
