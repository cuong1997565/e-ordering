<?php
namespace App\Models;

if (!defined('DISTRIBUTOR_ADMIN_ROLE_ID')) {define('DISTRIBUTOR_ADMIN_ROLE_ID', 'distributor_admin');}
if (!defined('DISTRIBUTOR_USER_ROLE_ID')) {define('DISTRIBUTOR_USER_ROLE_ID', 'distributor_user');}

class Role extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'display_name', 'description', 'permissions','active'
    ];

    public function getPermissionsAttribute($value)
    {
        if (is_string($value) && $value != '') {
            $value = explode(' ', $value);
        }
        return $value;
    }

    public function makeDefaultRoles() {
        $this->makeDefaultDistributorUserRole();
    }

    public function makeDefaultDistributorUserRole() {
        $permissions = [
            Permission::PERMISSION_CREATE_DISTRIBUTOR()->id,
            Permission::PERMISSION_VIEW_DISTRIBUTOR()->id,
            Permission::PERMISSION_LIST_DISTRIBUTORS()->id,
            Permission::PERMISSION_READ_DISTRIBUTOR()->id,
            Permission::PERMISSION_EDIT_DISTRIBUTOR()->id,
            Permission::PERMISSION_DELETE_DISTRIBUTOR()->id,
            Permission::PERMISSION_REMOVE_PRODUCT_FROM_DISTRIBUTOR()->id
        ];

        self::createRoles(
            'distributor_user',
            'authentication.roles.distributor_user.name',
            'authentication.roles.distributor_user.description',
            $permissions
        );
    }

    public static function createRoles($name, $displayName, $description, $permissions = []) {
        if (count($permissions) != 0) {
            $permissions = implode(" ", $permissions);
        } else {
            $permissions = '';
        }
        $data = [
            'name' => $name,
            'display_name' => $displayName,
            'description' => $description,
            'permissions' => $permissions
        ];
        Role::updateOrCreate(
            ['name' => $name],
            $data
        );
    }
}
