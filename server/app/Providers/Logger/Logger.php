<?php
namespace App\Providers\Logger;

use App\Models\Error;

class Logger {
    public $logManager;

    public $path;

    public $requestId;

    public $ipAddress;

    public $userId = null;

    public $method;

    public $errWhere;

    public $httpCode;

    public $errDetail;

    public $errMessage;

    public $message_log = '';

    public function __construct(\Illuminate\Log\LogManager $logManager)
    {
        app('translator')->setLocale('en');
        $this->logManager = $logManager;
    }

    public function LogError(Error $err) {
        $this->errWhere = $err->Where;
        $this->httpCode = $err->StatusCode;
        $this->errDetail = $err->DetailMessage;
        if ($err->Params) {
            $this->errMessage = trans('messages.'.$err->Id, $err->Params);
        } else {
            $this->errMessage = trans('messages.'.$err->Id);
        }
        $message = [
            'err_where' => $err->Where,
            'http_code' => $err->StatusCode,
            'err_details' => $err->DetailMessage
        ];
        $this->message_log = $this->message_log . $this->toMessage($message);
    }

    public function WriteLogError() {
        $message = 'msg: ' . $this->errMessage . ', ' . $this->message_log;
        $this->logManager->error($message);
    }

    public function WriteLogInfo($message) {
        if (is_array($message)) {
            $message = 'msg: { request_id: ' . $this->requestId . ', ' . $this->toMessage($message) . ' }';
            $this->logManager->info($message);
        }
    }
    public function with($path, $request_id, $ip_address, $user_id, $method) {
        $this->path = $path;
        $this->requestId = $request_id;
        $this->ipAddress = $ip_address;
        $this->userId = $user_id;
        $this->method = $method;
        $message = [
            'path' => $path,
            'request_id' => $request_id,
            'ip_addr' => $ip_address,
            'user_id' => $user_id,
            'method' => $method
        ];
        $this->message_log = $this->message_log . $this->toMessage($message);
    }

    public function toMessage($message) {
        if (is_array($message)) {
            $str = '';
            foreach($message as $key=>$item) {
                if ($item == null) {
                    $item = ' ';
                }
                $str .= $key.': '.$item.', ';
            }
            return rtrim($str, ',');
        }
        return '';
    }
}