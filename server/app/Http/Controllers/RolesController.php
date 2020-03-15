<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;

class RolesController extends Controller
{
    public function createRole(Role $role) {
        $this->validate($this->request, [
            'name' => 'required|regex:/^\S*$/u|regex:/^[a-zA-Z0-9?=.*!@#$%^&*_()\-\s]+$/u',
            'display_name' => 'required',
            'permissions' => 'required'
        ],
        [
            'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
            'name.regex' => trans('messages.api.regex.code.roles.app_error', ['Name' => 'code']),
            'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
            'permissions.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'permissions']),
        ]);
        $this->data['permissions'] = implode(" ", json_decode($this->data['permissions']));
        $this->saveRecord('Role', ACTIVE_TRUE);
    }
    public function updateRole(Role $role) {
        $this->validate($this->request, [
            'name' => 'required|regex:/^\S*$/u|regex:/^[a-zA-Z0-9?=.*!@#$%^&*_()\-\s]+$/u',
            'display_name' => 'required',
            'permissions' => 'required'
        ],
        [
            'name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'name']),
            'name.regex' => trans('messages.api.regex.code.roles.app_error', ['Name' => 'code']),
            'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
            'permissions.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'permissions']),
        ]);
        $this->data['permissions'] = implode(" ", json_decode($this->data['permissions']));
        $this->saveRecord('Role', ACTIVE_TRUE);
    }

    public function detailRole($roleId) {
        $role = Role::find($roleId);
        $this->output_json(['data' => $role], 200);
    }
    public function getAllRoles() {
        $roles = Role::select('id', 'name', 'display_name', 'description', 'permissions','active')->getDynamic();
        $this->output_json(['data' => $roles], 200);
    }
    public function getAllPermissions() {
        $permissions = collect(Permission::getAllPermissions())->groupBy('scope');
        $this->output_json(['data' => $permissions], 200);
    }
    public function deleteRole() {
        $this->deleteRecord('Role');
    }
}
