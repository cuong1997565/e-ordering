<?php

namespace App\App;

use App\Models\Error;
use App\Providers\Logger\Facade\AppLogger;
use App\Providers\Logger\Logger;
use App\Store\RoleStore;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthorizationRepository
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function userHasPermissionToDistributor($permission)
    {
        $roles = $this->request->curUser->roles;

        if ($roles != '' && !is_null($roles)) {
            $roles = explode(" ", $roles);
            $roles = $this->rolesGrantPermission($roles, $permission);
            return $roles;
        } else {
            return false;
        }
    }

    private function rolesGrantPermission($roles, $permissionId)
    {
        $roles = (new RoleStore())->getRoleByNames($roles);

        if (is_array($roles) && count($roles) == 0) {
            return false;
        }
        if ((is_object($roles)) && get_class($roles) == Error::class) {
            AppLogger::LogError($roles);
            return false;
        }

        foreach ($roles as $role) {
            $permissions = $role->permissions;
            if ($permissions != '') {
                $permissions = explode(" ", $permissions);
                foreach ($permissions as $permission) {
                    if ($permission == $permissionId) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
