<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetTokenAction
{
    public function execute($url, $username, $password)
    {
        $response = Http::withBasicAuth($username, $password)
            ->get($url . '?action=getToken');

        return $response->body();
    }
}
