<?php

namespace App\Http\Controllers;

use App\Actions\GetTokenAction;
use App\Actions\SendIdempotentRequestAction;
use App\Actions\SendJsonRequestAction;
use App\Actions\SendPostRequestAction;
use App\Actions\SendSummaryAction;

class ApiController extends Controller
{
    private GetTokenAction $getTokenAction;
    private SendPostRequestAction $sendPostRequestAction;
    private SendJsonRequestAction $sendJsonRequestAction;
    private SendIdempotentRequestAction $sendIdempotentRequestAction;
    private SendSummaryAction $sendSummaryAction;

    public function __construct()
    {
        $this->getTokenAction = resolve(GetTokenAction::class);
        $this->sendPostRequestAction = resolve(SendPostRequestAction::class);
        $this->sendJsonRequestAction = resolve(SendJsonRequestAction::class);
        $this->sendIdempotentRequestAction = resolve(SendIdempotentRequestAction::class);
        $this->sendSummaryAction = resolve(SendSummaryAction::class);
    }

    public function startProcess()
    {
        $url = 'https://testapi.esagaming.it/backend_atomo_qa/employee_api.php';
        $username = 'szakali.vandor.arpad@protonmail.com';
        $password = 'f4445594b6b04aaa2ba6a0a3c98bf78e099dec716f8ec190d4f0c77c29d8cc35';

        $token = $this->getTokenAction->execute($url, $username, $password);
        $this->sendPostRequestAction->execute($token, $url, $username, $password);
        $this->sendJsonRequestAction->execute($token, $url, $username, $password);
        $this->sendIdempotentRequestAction->execute($token, $url, $username, $password);
        $this->sendSummaryAction->execute($token, $url, $username, $password);
    }
}
