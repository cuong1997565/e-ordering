<?php

namespace App\Store;

use App\Models\Error;
use Illuminate\Support\Facades\DB;

class RoleStore
{
    public function getRoleByNames($roleNames) {
        if (!is_array($roleNames)) {
            return Error::NewAppError('model.role.is_valid.name.app_error', 'RoleStore.getRoleByNames', null, "name={$roleNames}", StatusBadRequest);
        }

        try {
            $roles = DB::table('roles')
                ->whereIn('name', $roleNames)->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.role.get_role_by_names.app_error', 'RoleStore.getRoleByNames', ['roleNames' => $roleNames], 'name in ' . $roleNames . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $roles;
    }
}
