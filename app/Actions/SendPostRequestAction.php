<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SendPostRequestAction
{
    public function execute($token, $url, $username, $password)
    {
        $currentTimestamp = Carbon::now()->format('Y-m-d H:i:s');

        $form =  [
            'token' => $token,
            'timestamp' => (int)$currentTimestamp
        ];

        $response = Http::withBasicAuth($username, $password)
            ->asForm()
            ->post($url . '?action=sendPostRequest', $form);

        return $response->status();
    }
}
