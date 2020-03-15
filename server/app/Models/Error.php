<?php
namespace App\Models;

use App\Providers\Logger\Facade\AppLogger;

class Error
{
    /**
     * Id of error
     * @var $Id
     */
    public $Id;

    /**
     * Message to be display to the end user
     * @var $Message
     */
    public $Message;

    /**
     * Message to be display to help developer
     * @var $Message
     */
    public $DetailMessage;

    /**
     * The RequestId that's also set in header
     * @var $RequestId
     */
    public $RequestId;

    /**
     * The Http status code
     * @var $StatusCode
     */
    public $StatusCode;

    /**
     * The function where it happened
     * @var $Where
     */
    public $Where;

    /**
     * The params extra
     * @var $Params
     */
    public $Params;

    public static function NewAppError($id, $where, $params, $detail, $status) {
        $ap = new Error();
        $ap->Id = $id;
        $ap->Where = $where;
        $ap->Message = $id;
        $ap->RequestId = app('uuid');
        $ap->DetailMessage = $detail;
        $ap->StatusCode = $status;
        $ap->Params = $params;

        AppLogger::LogError($ap);
        AppLogger::WriteLogError();
        return $ap;
    }

    public static function NewPermissionError($userId, $permissionId) {
        $ipr = new Error();
        $ipr->Id = "api.context.permissions.app_error";
        $ipr->Where = "Permissions";
        $ipr->DetailMessage = "userId=" . $userId . ", " . "permissionId=" . $permissionId;
        $ipr->RequestId = app('uuid');
        $ipr->StatusCode = StatusForbidden;
        $ipr->Params = null;
        AppLogger::LogError($ipr);
        AppLogger::WriteLogError();
        return $ipr;
    }
    public static function NewInvalidBodyParamError($parameter) {
        $ipr = new Error();
        $ipr->Id = "api.invalid_body_param.validate_error";
        $ipr->Where = "model.util";
        $ipr->DetailMessage = "";
        $ipr->RequestId = app('uuid');
        $ipr->StatusCode = StatusBadRequest;
        $ipr->Params = ['Name' => $parameter];
        AppLogger::LogError($ipr);
        AppLogger::WriteLogError();
        return $ipr;
    }

    public static function NewInvalidUrlParamError($parameter) {
        $ipr = new Error();
        $ipr->Id = "api.invalid_url_param.validate_error";
        $ipr->Where = "model.util";
        $ipr->DetailMessage = "";
        $ipr->RequestId = app('uuid');
        $ipr->StatusCode = StatusBadRequest;
        $ipr->Params = ['Name' => $parameter];
        AppLogger::LogError($ipr);
        AppLogger::WriteLogError();
        return $ipr;
    }

    public function toError() {
        return $this->Where . ": " . $this->Message . "," . $this->DetailMessage;
    }
}
