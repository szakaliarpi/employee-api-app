<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SendSummaryAction
{
    public function execute($token, $url, $username, $password)
    {
        $filePath = 'data.json';
        $jsonContents = Storage::get($filePath);
        $data = json_decode($jsonContents, true);
        $summaryData = [];

        foreach ($data as $item) {
            if ($item['resultCode'] === -1) {
                $item['resultCode'] = 0;
            } elseif ($item['resultCode'] === 0) {
                $item['resultCode'] = 1;
            }
            $uuid = $item['uuid'];
            $resultCode = $item['resultCode'];
            $summaryData[$uuid] = $resultCode;
        }

        $request_object = [
            'token' => $token,
            'requests' => $summaryData
        ];

        $response = Http::withBasicAuth($username, $password)
            ->asJson()
            ->post($url . '?action=sendSummary', $request_object);

        return $response->body();
    }
}
