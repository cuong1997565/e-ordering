<?php

namespace App\Models;

if (!defined('PERMISSION_SCOPE_ROLE')) {
    define('PERMISSION_SCOPE_ROLE', 'Role');
}
if (!defined('PERMISSION_SCOPE_DISTRIBUTOR')) {
    define('PERMISSION_SCOPE_DISTRIBUTOR', 'Distributor');
}
if (!defined('PERMISSION_SCOPE_PRICE')) {
    define('PERMISSION_SCOPE_PRICE', 'Price');
}
if (!defined('PERMISSION_SCOPE_CREDIT_ACCOUNT')) {
    define('PERMISSION_SCOPE_CREDIT_ACCOUNT', 'CreditAccount');
}
if (!defined('PERMISSION_SCOPE_SPECIFICATION')) {
    define('PERMISSION_SCOPE_SPECIFICATION', 'Specification');
}
if (!defined('PERMISSION_SCOPE_FACTORY')) {
    define('PERMISSION_SCOPE_FACTORY', 'Factory');
}
if (!defined('PERMISSION_SCOPE_AREA')) {
    define('PERMISSION_SCOPE_AREA', 'Area');
}
if (!defined('PERMISSION_SCOPE_CATEGORY')) {
    define('PERMISSION_SCOPE_CATEGORY', 'Category');
}
if (!defined('PERMISSION_SCOPE_BRAND')) {
    define('PERMISSION_SCOPE_BRAND', 'Brand');
}
if (!defined('PERMISSION_SCOPE_STORE')) {
    define('PERMISSION_SCOPE_STORE', 'Store');
}
if (!defined('PERMISSION_SCOPE_PRODUCT')) {
    define('PERMISSION_SCOPE_PRODUCT', 'Product');
}
if (!defined('PERMISSION_SCOPE_ADMIN')) {
    define('PERMISSION_SCOPE_ADMIN', 'Admin');
}
if (!defined('PERMISSION_SCOPE_MEMBER')) {
    define('PERMISSION_SCOPE_MEMBER', 'Member');
}
if (!defined('PERMISSION_SCOPE_UOM')) {
    define('PERMISSION_SCOPE_UOM', 'UOM');
}
if (!defined('PERMISSION_SCOPE_GRADE')) {
    define('PERMISSION_SCOPE_GRADE', 'Grade');
}
if (!defined('PERMISSION_SCOPE_FEATURE')) {
    define('PERMISSION_SCOPE_FEATURE', 'Feature');
}
if (!defined('PERMISSION_SCOPE_ATTRIBUTE')) {
    define('PERMISSION_SCOPE_ATTRIBUTE', 'Attribute');
}
if (!defined('PERMISSION_SCOPE_ATTRIBUTE_LIST_OF_VALUE')) {
    define('PERMISSION_SCOPE_ATTRIBUTE_LIST_OF_VALUE', 'Attribute_List_Of_Value');
}
if (!defined('PERMISSION_SCOPE_FEATURE_ITEM')) {
    define('PERMISSION_SCOPE_FEATURE_ITEM', 'Feature_Item');
}
if (!defined('PERMISSION_SCOPE_GRADE_GROUP')) {
    define('PERMISSION_SCOPE_GRADE_GROUP', 'Grade_Group');
}
if (!defined('PERMISSION_SCOPE_UOM_MULTIPLE')) {
    define('PERMISSION_SCOPE_UOM_MULTIPLE', 'UOM_Multiple');
}
if (!defined('PERMISSION_SCOPE_PRODUCT_TYPE')) {
    define('PERMISSION_SCOPE_PRODUCT_TYPE', 'Product_Type');
}

if (!defined('PERMISSION_SCOPE_DISCOUNT_TYPE')) {
    define('PERMISSION_SCOPE_DISCOUNT_TYPE', 'Discount_Type');
}

if (!defined('PERMISSION_SCOPE_ORDER')) {
    define('PERMISSION_SCOPE_ORDER', 'PO_Order');
}

if (!defined('PERMISSION_SCOPE_PO_ORDER')) {
    define('PERMISSION_SCOPE_PO_ORDER', 'PO_Order');
}

if (!defined('PERMISSION_SCOPE_SO_ORDER')) {
    define('PERMISSION_SCOPE_SO_ORDER', 'SO_Order');
}

if (!defined('PERMISSION_SCOPE_DN_ORDER')) {
    define('PERMISSION_SCOPE_DN_ORDER', 'DN_Order');
}

class Permission extends AppModel
{
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'description', 'scope'
    ];

    public function getNameAttribute($value)
    {
        return trans('messages.' . $value);
    }

    public function getDescriptionAttribute($value)
    {
        return trans('messages.' . $value);
    }

    protected static function createPermission($id, $name, $description, $scope)
    {
        $permission = new Permission();
        $permission->id = $id;
        $permission->name = $name;
        $permission->description = $description;
        $permission->scope = $scope;
        return $permission;
    }

    public static function getAllPermissions()
    {
        return [
            // Role
            self::PERMISSION_LIST_ROLES(),
            self::PERMISSION_CREATE_ROLE(),
            self::PERMISSION_EDIT_ROLE(),
//            self::PERMISSION_DELETE_ROLE(),

            // Distributor
            self::PERMISSION_LIST_DISTRIBUTORS(),
            self::PERMISSION_CREATE_DISTRIBUTOR(),
            self::PERMISSION_EDIT_DISTRIBUTOR(),
            self::PERMISSION_VIEW_DISTRIBUTOR(),
            self::PERMISSION_ADD_PRODUCT_DISTRIBUTOR(),
//            self::PERMISSION_READ_DISTRIBUTOR(),
//            self::PERMISSION_DELETE_DISTRIBUTOR(),
            self::PERMISSION_REMOVE_PRODUCT_FROM_DISTRIBUTOR(),

            // Price
            self::PERMISSION_LIST_PRICES(),
            self::PERMISSION_CREATE_PRICE(),
            self::PERMISSION_EDIT_PRICE(),
            self::PERMISSION_VIEW_PRICE(),

            // Discount type
            self::PERMISSION_LIST_DISCOUNTS_TYPE(),
            self::PERMISSION_CREATE_DISCOUNT_TYPE(),
            self::PERMISSION_EDIT_DISCOUNT_TYPE(),

            // Credit Account
            self::PERMISSION_LIST_CREDIT_ACCOUNTS(),
            self::PERMISSION_CREATE_CREDIT_ACCOUNT(),
            self::PERMISSION_EDIT_CREDIT_ACCOUNT(),
            self::PERMISSION_VIEW_CREDIT_ACCOUNT(),
//            self::PERMISSION_DELETE_CREDIT_ACCOUNT(),

            // Specification
            self::PERMISSION_LIST_SPECIFICATIONS(),
            self::PERMISSION_CREATE_SPECIFICATION(),
            self::PERMISSION_EDIT_SPECIFICATION(),
//            self::PERMISSION_DELETE_SPECIFICATION(),

            // Factory
            self::PERMISSION_LIST_FACTORIES(),
            self::PERMISSION_CREATE_FACTORY(),
            self::PERMISSION_EDIT_FACTORY(),
//            self::PERMISSION_DELETE_FACTORY(),

            // Area
            self::PERMISSION_LIST_AREAS(),
            self::PERMISSION_CREATE_AREA(),
            self::PERMISSION_EDIT_AREA(),
//            self::PERMISSION_DELETE_AREA(),

            // Category
            self::PERMISSION_LIST_CATEGORIES(),
            self::PERMISSION_CREATE_CATEGORY(),
            self::PERMISSION_EDIT_CATEGORY(),
//            self::PERMISSION_DELETE_CATEGORY(),

            // Brand
//            self::PERMISSION_LIST_BRANDS(),
//            self::PERMISSION_CREATE_BRAND(),
//            self::PERMISSION_EDIT_BRAND(),
//            self::PERMISSION_DELETE_BRAND(),

            // Store
            self::PERMISSION_LIST_STORES(),
            self::PERMISSION_CREATE_STORE(),
            self::PERMISSION_EDIT_STORE(),
//            self::PERMISSION_DELETE_STORE(),

            // Product
            self::PERMISSION_LIST_PRODUCTS(),
            self::PERMISSION_CREATE_PRODUCT(),
            self::PERMISSION_EDIT_PRODUCT(),
//            self::PERMISSION_DELETE_PRODUCT(),

            // PO Order
            self::PERMISSION_LIST_ORDERS(),
            self::PERMISSION_CREATE_ORDER(),
            self::PERMISSION_EDIT_ORDER(),
//            self::PERMISSION_DELETE_ORDER(),
            self::PERMISSION_UPDATE_ORDER(),
            self::PERMISSION_APPROVE_ORDER(),
            self::PERMISSION_REVIEW_ORDER(),
            self::PERMISSION_REJECT_ORDER(),
            self::PERMISSION_REJECT_ITEM_PRODUCT_ORDER(),
            // So Order
            self::PERMISSION_LIST_ALL_SO_ORDER(),
//            self::PERMISSION_LIST_SO_FROM_FACTORY_ORDER(),
            self::PERMISSION_CREATE_SO_ORDER(),
            self::PERMISSION_EDIT_SO_ORDER(),
//            self::PERMISSION_DELETE_SO_ORDER(),

            // Dn Order
            self::PERMISSION_LIST_ALL_DN_ORDER(),
//            self::PERMISSION_LIST_DN_FROM_FACTORY_ORDER(),
            self::PERMISSION_CREATE_DN_ORDER(),
            self::PERMISSION_EDIT_DN_ORDER(),
//            self::PERMISSION_DELETE_DN_ORDER(),
            self::PERMISSION_UPDATE_DN_ORDER(),

            self::PERMISSION_CONFIRM_UPDATE_DN_ORDER(),

            self::PERMISSION_REVERSE_DN_ORDER(),

            self::PERMISSION_APPROVE_DN_ORDER(),

            self::PERMISSION_REJECT_DN_ORDER(),

            /** ----------------------Master data --------------**/
            // Master admin data
            self::PERMISSION_LIST_ADMINS(),
            self::PERMISSION_CREATE_ADMIN(),
            self::PERMISSION_EDIT_ADMIN(),
            self::PERMISSION_VIEW_ADMIN(),
//            self::PERMISSION_DELETE_ADMIN(),

            // Master member data
            self::PERMISSION_LIST_MEMBERS(),
            self::PERMISSION_CREATE_MEMBER(),
            self::PERMISSION_EDIT_MEMBER(),
            self::PERMISSION_VIEW_MEMBER(),
//            self::PERMISSION_DELETE_MEMBER(),

            // UOM
            self::PERMISSION_LIST_UOMS(),
            self::PERMISSION_CREATE_UOM(),
            self::PERMISSION_EDIT_UOM(),

            // UOM_Multiple
            self::PERMISSION_LIST_UOM_MULTIPLES(),
            self::PERMISSION_CREATE_UOM_MULTIPLE(),
            self::PERMISSION_EDIT_UOM_MULTIPLE(),

            // PRODUCT_TYPE
            self::PERMISSION_LIST_PRODUCT_TYPES(),
            self::PERMISSION_CREATE_PRODUCT_TYPE(),
            self::PERMISSION_EDIT_PRODUCT_TYPE(),

            // GRADEPERMISSION_LIST_ORDERS
            self::PERMISSION_LIST_GRADES(),
            self::PERMISSION_CREATE_GRADE(),
            self::PERMISSION_EDIT_GRADE(),

            // GRADE_GROUP
            self::PERMISSION_LIST_GRADE_GROUPS(),
            self::PERMISSION_CREATE_GRADE_GROUP(),
            self::PERMISSION_EDIT_GRADE_GROUP(),

            // FEATURE
            self::PERMISSION_LIST_FEATURES(),
            self::PERMISSION_CREATE_FEATURE(),
            self::PERMISSION_EDIT_FEATURE(),

            // FEATURE_Item
            self::PERMISSION_LIST_FEATURE_ITEMS(),
            self::PERMISSION_CREATE_FEATURE_ITEM(),
            self::PERMISSION_EDIT_FEATURE_ITEM(),

            // ATTRIBUTE
            self::PERMISSION_LIST_ATTRIBUTES(),
            self::PERMISSION_CREATE_ATTRIBUTE(),
            self::PERMISSION_EDIT_ATTRIBUTE(),

            // ATTRIBUTE_LIST_OF_VALUE
            self::PERMISSION_LIST_ATTRIBUTE_LIST_OF_VALUES(),
            self::PERMISSION_CREATE_ATTRIBUTE_LIST_OF_VALUE(),
            self::PERMISSION_EDIT_ATTRIBUTE_LIST_OF_VALUE(),
        ];
    }

    /** -------------------------------------Role Permissions ------------------------------------------------- */
    public static function PERMISSION_LIST_ROLES()
    {
        return self::createPermission(
            'list_roles',
            'authentication.permissions.list_role.name',
            'authentication.permissions.list_role.description',
            PERMISSION_SCOPE_ROLE
        );
    }

    public static function PERMISSION_CREATE_ROLE()
    {
        return self::createPermission(
            'create_role',
            'authentication.permissions.create_role.name',
            'authentication.permissions.create_role.description',
            PERMISSION_SCOPE_ROLE
        );

    }

    public static function PERMISSION_DELETE_ROLE()
    {
        return self::createPermission(
            'delete_role',
            'authentication.permissions.delete_role.name',
            'authentication.permissions.delete_role.description',
            PERMISSION_SCOPE_ROLE
        );
    }

    public static function PERMISSION_EDIT_ROLE()
    {
        return self::createPermission(
            'edit_role',
            'authentication.permissions.edit_role.name',
            'authentication.permissions.edit_role.description',
            PERMISSION_SCOPE_ROLE
        );
    }

    /** ------------------------------------ Distributor Permissions -----------------------------------------------**/
    public static function PERMISSION_READ_DISTRIBUTOR()
    {
        return self::createPermission(
            'read_distributor',
            'authentication.permissions.read_distributor.name',
            'authentication.permissions.read_distributor.description',
            PERMISSION_SCOPE_DISTRIBUTOR
        );
    }

    public static function PERMISSION_VIEW_DISTRIBUTOR()
    {
        return self::createPermission(
            'view_distributor',
            'authentication.permissions.view_distributor.name',
            'authentication.permissions.view_distributor.description',
            PERMISSION_SCOPE_DISTRIBUTOR
        );
    }

    public static function PERMISSION_LIST_DISTRIBUTORS()
    {
        return self::createPermission(
            'list_distributor',
            'authentication.permissions.list_distributor.name',
            'authentication.permissions.list_distributor.description',
            PERMISSION_SCOPE_DISTRIBUTOR
        );
    }

    public static function PERMISSION_CREATE_DISTRIBUTOR()
    {
        return self::createPermission(
            'create_distributor',
            'authentication.permissions.create_distributor.name',
            'authentication.permissions.create_distributor.description',
            PERMISSION_SCOPE_DISTRIBUTOR
        );
    }

    public static function PERMISSION_EDIT_DISTRIBUTOR()
    {
        return self::createPermission(
            'edit_distributor',
            'authentication.permissions.edit_distributor.name',
            'authentication.permissions.edit_distributor.description',
            PERMISSION_SCOPE_DISTRIBUTOR
        );
    }

    public static function PERMISSION_ADD_PRODUCT_DISTRIBUTOR() {
        return self::createPermission(
            'add_product_distributor',
            'authentication.permissions.add_product_distributor.name',
            'authentication.permissions.add_product_distributor.description',
            PERMISSION_SCOPE_DISTRIBUTOR
        );
    }

    public static function PERMISSION_DELETE_DISTRIBUTOR()
    {
        return self::createPermission(
            'delete_distributor',
            'authentication.permissions.delete_distributor.name',
            'authentication.permissions.delete_distributor.description',
            PERMISSION_SCOPE_DISTRIBUTOR
        );
    }

    public static function PERMISSION_REMOVE_PRODUCT_FROM_DISTRIBUTOR()
    {
        return self::createPermission(
            'remove_product_from_distributor',
            'authentication.permissions.remove_product_from_distributor.name',
            'authentication.permissions.remove_product_from_distributor.description',
            PERMISSION_SCOPE_DISTRIBUTOR
        );
    }

    /** ------------------------------------ Price Permissions -----------------------------------------------**/

    public static function PERMISSION_VIEW_PRICE()
    {
        return self::createPermission(
            'view_price',
            'authentication.permissions.view_price.name',
            'authentication.permissions.view_price.description',
            PERMISSION_SCOPE_PRICE
        );
    }

    public static function PERMISSION_LIST_PRICES()
    {
        return self::createPermission(
            'list_price',
            'authentication.permissions.list_price.name',
            'authentication.permissions.list_price.description',
            PERMISSION_SCOPE_PRICE
        );
    }

    public static function PERMISSION_CREATE_PRICE()
    {
        return self::createPermission(
            'create_price',
            'authentication.permissions.create_price.name',
            'authentication.permissions.create_price.description',
            PERMISSION_SCOPE_PRICE
        );
    }

    public static function PERMISSION_EDIT_PRICE()
    {
        return self::createPermission(
            'edit_price',
            'authentication.permissions.edit_price.name',
            'authentication.permissions.edit_price.description',
            PERMISSION_SCOPE_PRICE
        );
    }

    public static function PERMISSION_DELETE_PRICE()
    {
        return self::createPermission(
            'delete_price',
            'authentication.permissions.delete_price.name',
            'authentication.permissions.delete_price.description',
            PERMISSION_SCOPE_PRICE
        );
    }

    /** ------------------------------------ Discount Type Permissions -----------------------------------------------**/

    public static function PERMISSION_LIST_DISCOUNTS_TYPE()
    {
        return self::createPermission(
            'list_discount_type',
            'authentication.permissions.list_discount_type.name',
            'authentication.permissions.list_discount_type.description',
            PERMISSION_SCOPE_DISCOUNT_TYPE
        );
    }

    public static function PERMISSION_CREATE_DISCOUNT_TYPE()
    {
        return self::createPermission(
            'create_discount_type',
            'authentication.permissions.create_discount_type.name',
            'authentication.permissions.create_discount_type.description',
            PERMISSION_SCOPE_DISCOUNT_TYPE
        );
    }

    public static function PERMISSION_EDIT_DISCOUNT_TYPE()
    {
        return self::createPermission(
            'edit_discount_type',
            'authentication.permissions.edit_discount_type.name',
            'authentication.permissions.edit_discount_type.description',
            PERMISSION_SCOPE_DISCOUNT_TYPE
        );
    }

    /** ------------------------------- Credit Account Permissions -----------------------------------------*/
    public static function PERMISSION_LIST_CREDIT_ACCOUNTS()
    {
        return self::createPermission(
            'list_credit_accounts',
            'authentication.permissions.list_credit_accounts.name',
            'authentication.permissions.list_credit_accounts.description',
            PERMISSION_SCOPE_CREDIT_ACCOUNT
        );
    }

    public static function PERMISSION_CREATE_CREDIT_ACCOUNT()
    {
        return self::createPermission(
            'create_credit_account',
            'authentication.permissions.create_credit_account.name',
            'authentication.permissions.create_credit_account.description',
            PERMISSION_SCOPE_CREDIT_ACCOUNT
        );
    }

    public static function PERMISSION_VIEW_CREDIT_ACCOUNT()
    {
        return self::createPermission(
            'view_credit_account',
            'authentication.permissions.view_credit_account.name',
            'authentication.permissions.view_credit_account.description',
            PERMISSION_SCOPE_CREDIT_ACCOUNT
        );
    }

    public static function PERMISSION_EDIT_CREDIT_ACCOUNT()
    {
        return self::createPermission(
            'edit_credit_account',
            'authentication.permissions.edit_credit_account.name',
            'authentication.permissions.edit_credit_account.description',
            PERMISSION_SCOPE_CREDIT_ACCOUNT
        );
    }

    public static function PERMISSION_DELETE_CREDIT_ACCOUNT()
    {
        return self::createPermission(
            'delete_credit_account',
            'authentication.permissions.delete_credit_account.name',
            'authentication.permissions.delete_credit_account.description',
            PERMISSION_SCOPE_CREDIT_ACCOUNT
        );
    }

    /** ------------------------------- Speicification Permissions -----------------------------------------*/
    public static function PERMISSION_LIST_SPECIFICATIONS()
    {
        return self::createPermission(
            'list_specifications',
            'authentication.permissions.list_specifications.name',
            'authentication.permissions.list_specifications.description',
            PERMISSION_SCOPE_SPECIFICATION
        );
    }

    public static function PERMISSION_CREATE_SPECIFICATION()
    {
        return self::createPermission(
            'create_specification',
            'authentication.permissions.create_specification.name',
            'authentication.permissions.create_specification.description',
            PERMISSION_SCOPE_SPECIFICATION
        );
    }

    public static function PERMISSION_EDIT_SPECIFICATION()
    {
        return self::createPermission(
            'edit_specification',
            'authentication.permissions.edit_specification.name',
            'authentication.permissions.edit_specification.description',
            PERMISSION_SCOPE_SPECIFICATION
        );
    }

    public static function PERMISSION_DELETE_SPECIFICATION()
    {
        return self::createPermission(
            'delete_specification',
            'authentication.permissions.delete_specification.name',
            'authentication.permissions.delete_specification.description',
            PERMISSION_SCOPE_SPECIFICATION
        );
    }

    /** ------------------------------- Factory Permissions -----------------------------------------*/
    public static function PERMISSION_LIST_FACTORIES()
    {
        return self::createPermission(
            'list_factories',
            'authentication.permissions.list_factories.name',
            'authentication.permissions.list_factories.description',
            PERMISSION_SCOPE_FACTORY
        );
    }

    public static function PERMISSION_CREATE_FACTORY()
    {
        return self::createPermission(
            'create_factory',
            'authentication.permissions.create_factory.name',
            'authentication.permissions.create_factory.description',
            PERMISSION_SCOPE_FACTORY
        );
    }

    public static function PERMISSION_EDIT_FACTORY()
    {
        return self::createPermission(
            'edit_factory',
            'authentication.permissions.edit_factory.name',
            'authentication.permissions.edit_factory.description',
            PERMISSION_SCOPE_FACTORY
        );
    }

    public static function PERMISSION_DELETE_FACTORY()
    {
        return self::createPermission(
            'delete_factory',
            'authentication.permissions.delete_factory.name',
            'authentication.permissions.delete_factory.description',
            PERMISSION_SCOPE_FACTORY
        );
    }

    /** ------------------------------- Area Permissions -----------------------------------------*/
    public static function PERMISSION_LIST_AREAS()
    {
        return self::createPermission(
            'list_areas',
            'authentication.permissions.list_areas.name',
            'authentication.permissions.list_areas.description',
            PERMISSION_SCOPE_AREA
        );
    }

    public static function PERMISSION_CREATE_AREA()
    {
        return self::createPermission(
            'create_area',
            'authentication.permissions.create_area.name',
            'authentication.permissions.create_area.description',
            PERMISSION_SCOPE_AREA
        );
    }

    public static function PERMISSION_EDIT_AREA()
    {
        return self::createPermission(
            'edit_area',
            'authentication.permissions.edit_area.name',
            'authentication.permissions.edit_area.description',
            PERMISSION_SCOPE_AREA
        );
    }

//    public static function PERMISSION_DELETE_AREA()
//    {
//        return self::createPermission(
//            'delete_area',
//            'authentication.permissions.delete_area.name',
//            'authentication.permissions.delete_area.description',
//            PERMISSION_SCOPE_AREA
//        );
//    }

    /** ------------------------------- Category Permissions -----------------------------------------*/
    public static function PERMISSION_LIST_CATEGORIES()
    {
        return self::createPermission(
            'list_categories',
            'authentication.permissions.list_categories.name',
            'authentication.permissions.list_categories.description',
            PERMISSION_SCOPE_CATEGORY
        );
    }

    public static function PERMISSION_CREATE_CATEGORY()
    {
        return self::createPermission(
            'create_category',
            'authentication.permissions.create_category.name',
            'authentication.permissions.create_category.description',
            PERMISSION_SCOPE_CATEGORY
        );
    }

    public static function PERMISSION_EDIT_CATEGORY()
    {
        return self::createPermission(
            'edit_category',
            'authentication.permissions.edit_category.name',
            'authentication.permissions.edit_category.description',
            PERMISSION_SCOPE_CATEGORY
        );
    }

    public static function PERMISSION_DELETE_CATEGORY()
    {
        return self::createPermission(
            'delete_category',
            'authentication.permissions.delete_category.name',
            'authentication.permissions.delete_category.description',
            PERMISSION_SCOPE_CATEGORY
        );
    }

    /** ------------------------------- Brand Permissions -----------------------------------------*/
    public static function PERMISSION_LIST_BRANDS()
    {
        return self::createPermission(
            'list_brands',
            'authentication.permissions.list_brands.name',
            'authentication.permissions.list_brands.description',
            PERMISSION_SCOPE_BRAND
        );
    }

    public static function PERMISSION_CREATE_BRAND()
    {
        return self::createPermission(
            'create_brand',
            'authentication.permissions.create_brand.name',
            'authentication.permissions.create_brand.description',
            PERMISSION_SCOPE_BRAND
        );
    }

    public static function PERMISSION_EDIT_BRAND()
    {
        return self::createPermission(
            'edit_brand',
            'authentication.permissions.edit_brand.name',
            'authentication.permissions.edit_brand.description',
            PERMISSION_SCOPE_BRAND
        );
    }

    public static function PERMISSION_DELETE_BRAND()
    {
        return self::createPermission(
            'delete_brand',
            'authentication.permissions.delete_brand.name',
            'authentication.permissions.delete_brand.description',
            PERMISSION_SCOPE_BRAND
        );
    }

    /** ------------------------------- Store Permissions -----------------------------------------*/
    public static function PERMISSION_LIST_STORES()
    {
        return self::createPermission(
            'list_stores',
            'authentication.permissions.list_stores.name',
            'authentication.permissions.list_stores.description',
            PERMISSION_SCOPE_STORE
        );
    }

    public static function PERMISSION_CREATE_STORE()
    {
        return self::createPermission(
            'create_store',
            'authentication.permissions.create_store.name',
            'authentication.permissions.create_store.description',
            PERMISSION_SCOPE_STORE
        );
    }

    public static function PERMISSION_EDIT_STORE()
    {
        return self::createPermission(
            'edit_store',
            'authentication.permissions.edit_store.name',
            'authentication.permissions.edit_store.description',
            PERMISSION_SCOPE_STORE
        );
    }

    public static function PERMISSION_DELETE_STORE()
    {
        return self::createPermission(
            'delete_store',
            'authentication.permissions.delete_store.name',
            'authentication.permissions.delete_store.description',
            PERMISSION_SCOPE_STORE
        );
    }

    /** ------------------------------- Product Permissions -----------------------------------------*/
    public static function PERMISSION_LIST_PRODUCTS()
    {
        return self::createPermission(
            'list_products',
            'authentication.permissions.list_products.name',
            'authentication.permissions.list_products.description',
            PERMISSION_SCOPE_PRODUCT
        );
    }

    public static function PERMISSION_CREATE_PRODUCT()
    {
        return self::createPermission(
            'create_product',
            'authentication.permissions.create_product.name',
            'authentication.permissions.create_product.description',
            PERMISSION_SCOPE_PRODUCT
        );
    }

    public static function PERMISSION_EDIT_PRODUCT()
    {
        return self::createPermission(
            'edit_product',
            'authentication.permissions.edit_product.name',
            'authentication.permissions.edit_product.description',
            PERMISSION_SCOPE_PRODUCT
        );
    }

    public static function PERMISSION_DELETE_PRODUCT()
    {
        return self::createPermission(
            'delete_product',
            'authentication.permissions.delete_product.name',
            'authentication.permissions.delete_product.description',
            PERMISSION_SCOPE_PRODUCT
        );
    }

    /** ------------------------------- Order Permissions -----------------------------------------*/
    public static function PERMISSION_LIST_ORDERS()
    {
        return self::createPermission(
            'list_orders',
            'authentication.permissions.list_orders.name',
            'authentication.permissions.list_orders.description',
            PERMISSION_SCOPE_ORDER
        );
    }

    public static function PERMISSION_CREATE_ORDER()
    {
        return self::createPermission(
            'create_order',
            'authentication.permissions.create_order.name',
            'authentication.permissions.create_order.description',
            PERMISSION_SCOPE_ORDER
        );
    }

    public static function PERMISSION_EDIT_ORDER()
    {
        return self::createPermission(
            'edit_order',
            'authentication.permissions.edit_order.name',
            'authentication.permissions.edit_order.description',
            PERMISSION_SCOPE_ORDER
        );
    }

    public static function PERMISSION_DELETE_ORDER()
    {
        return self::createPermission(
            'delete_order',
            'authentication.permissions.delete_order.name',
            'authentication.permissions.delete_order.description',
            PERMISSION_SCOPE_ORDER
        );
    }

    public static function PERMISSION_REJECT_ITEM_PRODUCT_ORDER()
    {
        return self::createPermission(
            'reject_item_product_order',
            'authentication.permissions.reject_item_product_order.name',
            'authentication.permissions.reject_item_product_order.description',
            PERMISSION_SCOPE_ORDER
        );
    }

    public static function PERMISSION_UPDATE_ORDER() {
        return self::createPermission(
            'update_order',
            'authentication.permissions.update_order.name',
            'authentication.permissions.update_order.description',
            PERMISSION_SCOPE_ORDER
        );
    }

    public static function PERMISSION_APPROVE_ORDER() {
        return self::createPermission(
            'approve_order',
            'authentication.permissions.approve_order.name',
            'authentication.permissions.approve_order.description',
            PERMISSION_SCOPE_ORDER
        );
    }

    public static function PERMISSION_REVIEW_ORDER() {
        return self::createPermission(
            'review_order',
            'authentication.permissions.review_order.name',
            'authentication.permissions.review_order.description',
            PERMISSION_SCOPE_ORDER
        );
    }

    public static function PERMISSION_REJECT_ORDER() {
        return self::createPermission(
            'reject_order',
            'authentication.permissions.reject_order.name',
            'authentication.permissions.reject_order.description',
            PERMISSION_SCOPE_ORDER
        );
    }

    /** ------------------------------------ Admin Master data Permissions ----------------------------------- */
    public static function PERMISSION_LIST_ADMINS()
    {
        return self::createPermission(
            'list_admins',
            'authentication.permissions.list_admins.name',
            'authentication.permissions.list_admins.description',
            PERMISSION_SCOPE_ADMIN
        );
    }

    public static function PERMISSION_CREATE_ADMIN()
    {
        return self::createPermission(
            'create_admin',
            'authentication.permissions.create_admin.name',
            'authentication.permissions.create_admin.description',
            PERMISSION_SCOPE_ADMIN
        );
    }

    public static function PERMISSION_VIEW_ADMIN()
    {
        return self::createPermission(
            'view_admin',
            'authentication.permissions.view_admin.name',
            'authentication.permissions.view_admin.description',
            PERMISSION_SCOPE_ADMIN
        );
    }

    public static function PERMISSION_EDIT_ADMIN()
    {
        return self::createPermission(
            'edit_admin',
            'authentication.permissions.edit_admin.name',
            'authentication.permissions.edit_admin.description',
            PERMISSION_SCOPE_ADMIN
        );
    }

//    public static function PERMISSION_DELETE_ADMIN()
//    {
//        return self::createPermission(
//            'delete_admin',
//            'authentication.permissions.delete_admin.name',
//            'authentication.permissions.delete_admin.description',
//            PERMISSION_SCOPE_ADMIN
//        );
//    }

    /** ------------------------------- Member Master data Permissions --------------------------------------- */
    public static function PERMISSION_LIST_MEMBERS()
    {
        return self::createPermission(
            'list_members',
            'authentication.permissions.list_members.name',
            'authentication.permissions.list_members.description',
            PERMISSION_SCOPE_MEMBER
        );
    }

    public static function PERMISSION_CREATE_MEMBER()
    {
        return self::createPermission(
            'create_member',
            'authentication.permissions.create_member.name',
            'authentication.permissions.create_member.description',
            PERMISSION_SCOPE_MEMBER
        );
    }

    public static function PERMISSION_VIEW_MEMBER()
    {
        return self::createPermission(
            'view_member',
            'authentication.permissions.view_member.name',
            'authentication.permissions.view_member.description',
            PERMISSION_SCOPE_MEMBER
        );
    }

    public static function PERMISSION_EDIT_MEMBER()
    {
        return self::createPermission(
            'edit_member',
            'authentication.permissions.edit_member.name',
            'authentication.permissions.edit_member.description',
            PERMISSION_SCOPE_MEMBER
        );
    }

    public static function PERMISSION_DELETE_MEMBER()
    {
        return self::createPermission(
            'delete_member',
            'authentication.permissions.delete_member.name',
            'authentication.permissions.delete_member.description',
            PERMISSION_SCOPE_MEMBER
        );
    }

    /** ------------------------------------ UOM Masterdata -------------------------------------------------**/

    public static function PERMISSION_LIST_UOMS()
    {
        return self::createPermission(
            'list_uoms',
            'authentication.permissions.list_uoms.name',
            'authentication.permissions.list_uoms.description',
            PERMISSION_SCOPE_UOM
        );
    }

    public static function PERMISSION_EDIT_UOM()
    {
        return self::createPermission(
            'edit_uom',
            'authentication.permissions.edit_uom.name',
            'authentication.permissions.edit_uom.description',
            PERMISSION_SCOPE_UOM
        );
    }

    public static function PERMISSION_CREATE_UOM()
    {
        return self::createPermission(
            'create_uom',
            'authentication.permissions.create_uom.name',
            'authentication.permissions.create_uom.description',
            PERMISSION_SCOPE_UOM
        );
    }

    /** ------------------------------------ UOM_ Multiple Masterdata -------------------------------------------------**/

    public static function PERMISSION_LIST_UOM_MULTIPLES()
    {
        return self::createPermission(
            'list_uom_multiples',
            'authentication.permissions.list_uom_multiples.name',
            'authentication.permissions.list_uom_multiples.description',
            PERMISSION_SCOPE_UOM_MULTIPLE
        );
    }

    public static function PERMISSION_EDIT_UOM_MULTIPLE()
    {
        return self::createPermission(
            'edit_uom_multiple',
            'authentication.permissions.edit_uom_multiple.name',
            'authentication.permissions.edit_uom_multiple.description',
            PERMISSION_SCOPE_UOM_MULTIPLE
        );
    }

    public static function PERMISSION_CREATE_UOM_MULTIPLE()
    {
        return self::createPermission(
            'create_uom_multiple',
            'authentication.permissions.create_uom_multiple.name',
            'authentication.permissions.create_uom_multiple.description',
            PERMISSION_SCOPE_UOM_MULTIPLE
        );
    }

    /** ------------------------------------ PRODUCT_Type Masterdata -------------------------------------------------**/

    public static function PERMISSION_LIST_PRODUCT_TYPES()
    {
        return self::createPermission(
            'list_product_types',
            'authentication.permissions.list_product_types.name',
            'authentication.permissions.list_product_types.description',
            PERMISSION_SCOPE_PRODUCT_TYPE
        );
    }

    public static function PERMISSION_EDIT_PRODUCT_TYPE()
    {
        return self::createPermission(
            'edit_product_type',
            'authentication.permissions.edit_product_type.name',
            'authentication.permissions.edit_product_type.description',
            PERMISSION_SCOPE_PRODUCT_TYPE
        );
    }

    public static function PERMISSION_CREATE_PRODUCT_TYPE()
    {
        return self::createPermission(
            'create_product_type',
            'authentication.permissions.create_product_type.name',
            'authentication.permissions.create_product_type.description',
            PERMISSION_SCOPE_PRODUCT_TYPE
        );
    }

    /** ------------------------------------ GRADE Masterdata -------------------------------------------------**/

    public static function PERMISSION_LIST_GRADES()
    {
        return self::createPermission(
            'list_grades',
            'authentication.permissions.list_grades.name',
            'authentication.permissions.list_grades.description',
            PERMISSION_SCOPE_GRADE
        );
    }

    public static function PERMISSION_EDIT_GRADE()
    {
        return self::createPermission(
            'edit_grade',
            'authentication.permissions.edit_grade.name',
            'authentication.permissions.edit_grade.description',
            PERMISSION_SCOPE_GRADE
        );
    }

    public static function PERMISSION_CREATE_GRADE()
    {
        return self::createPermission(
            'create_grade',
            'authentication.permissions.create_grade.name',
            'authentication.permissions.create_grade.description',
            PERMISSION_SCOPE_GRADE
        );
    }

    /** ------------------------------------ GRADE_GROUP Masterdata -------------------------------------------------**/

    public static function PERMISSION_LIST_GRADE_GROUPS()
    {
        return self::createPermission(
            'list_grade_groups',
            'authentication.permissions.list_grade_groups.name',
            'authentication.permissions.list_grade_groups.description',
            PERMISSION_SCOPE_GRADE_GROUP
        );
    }

    public static function PERMISSION_EDIT_GRADE_GROUP()
    {
        return self::createPermission(
            'edit_grade_group',
            'authentication.permissions.edit_grade_group.name',
            'authentication.permissions.edit_grade_group.description',
            PERMISSION_SCOPE_GRADE_GROUP
        );
    }

    public static function PERMISSION_CREATE_GRADE_GROUP()
    {
        return self::createPermission(
            'create_grade_group',
            'authentication.permissions.create_grade_group.name',
            'authentication.permissions.create_grade_group.description',
            PERMISSION_SCOPE_GRADE_GROUP
        );
    }

    /** ------------------------------------ FEATURE Masterdata -------------------------------------------------**/

    public static function PERMISSION_LIST_FEATURES()
    {
        return self::createPermission(
            'list_features',
            'authentication.permissions.list_features.name',
            'authentication.permissions.list_features.description',
            PERMISSION_SCOPE_FEATURE
        );
    }

    public static function PERMISSION_EDIT_FEATURE()
    {
        return self::createPermission(
            'edit_feature',
            'authentication.permissions.edit_feature.name',
            'authentication.permissions.edit_feature.description',
            PERMISSION_SCOPE_FEATURE
        );
    }

    public static function PERMISSION_CREATE_FEATURE()
    {
        return self::createPermission(
            'create_feature',
            'authentication.permissions.create_feature.name',
            'authentication.permissions.create_feature.description',
            PERMISSION_SCOPE_FEATURE
        );
    }

    /** ------------------------------------ FEATURE_ITEM Masterdata -------------------------------------------------**/

    public static function PERMISSION_LIST_FEATURE_ITEMS()
    {
        return self::createPermission(
            'list_feature_items',
            'authentication.permissions.list_feature_items.name',
            'authentication.permissions.list_feature_items.description',
            PERMISSION_SCOPE_FEATURE_ITEM
        );
    }

    public static function PERMISSION_EDIT_FEATURE_ITEM()
    {
        return self::createPermission(
            'edit_feature_item',
            'authentication.permissions.edit_feature_item.name',
            'authentication.permissions.edit_feature_item.description',
            PERMISSION_SCOPE_FEATURE_ITEM
        );
    }

    public static function PERMISSION_CREATE_FEATURE_ITEM()
    {
        return self::createPermission(
            'create_feature_item',
            'authentication.permissions.create_feature_item.name',
            'authentication.permissions.create_feature_item.description',
            PERMISSION_SCOPE_FEATURE_ITEM
        );
    }

    /** ------------------------------------ ATTRIBUTE Masterdata -------------------------------------------------**/

    public static function PERMISSION_LIST_ATTRIBUTES()
    {
        return self::createPermission(
            'list_attributes',
            'authentication.permissions.list_attributes.name',
            'authentication.permissions.list_attributes.description',
            PERMISSION_SCOPE_ATTRIBUTE
        );
    }

    public static function PERMISSION_EDIT_ATTRIBUTE()
    {
        return self::createPermission(
            'edit_attribute',
            'authentication.permissions.edit_attribute.name',
            'authentication.permissions.edit_attribute.description',
            PERMISSION_SCOPE_ATTRIBUTE
        );
    }

    public static function PERMISSION_CREATE_ATTRIBUTE()
    {
        return self::createPermission(
            'create_attribute',
            'authentication.permissions.create_attribute.name',
            'authentication.permissions.create_attribute.description',
            PERMISSION_SCOPE_ATTRIBUTE
        );
    }

    /** ------------------------------------ ATTRIBUTE_LIST_OF_VALUE Masterdata -------------------------------------------------**/

    public static function PERMISSION_LIST_ATTRIBUTE_LIST_OF_VALUES()
    {
        return self::createPermission(
            'list_attribute_list_of_values',
            'authentication.permissions.list_attribute_list_of_values.name',
            'authentication.permissions.list_attribute_list_of_values.description',
            PERMISSION_SCOPE_ATTRIBUTE_LIST_OF_VALUE
        );
    }

    public static function PERMISSION_EDIT_ATTRIBUTE_LIST_OF_VALUE()
    {
        return self::createPermission(
            'edit_attribute_list_of_value',
            'authentication.permissions.edit_attribute_list_of_value.name',
            'authentication.permissions.edit_attribute_list_of_value.description',
            PERMISSION_SCOPE_ATTRIBUTE_LIST_OF_VALUE
        );
    }

    public static function PERMISSION_CREATE_ATTRIBUTE_LIST_OF_VALUE()
    {
        return self::createPermission(
            'create_attribute_list_of_value',
            'authentication.permissions.create_attribute_list_of_value.name',
            'authentication.permissions.create_attribute_list_of_value.description',
            PERMISSION_SCOPE_ATTRIBUTE_LIST_OF_VALUE
        );
    }

    /** ------------------------------------ Order Permissions -----------------------------------------------**/

    /** ----- PO ----- */
    public static function PERMISSION_READ_PO_ORDER()
    {
        return self::createPermission(
            'read_po_order',
            'authentication.permissions.read_po_order.name',
            'authentication.permissions.read_po_order.description',
            PERMISSION_SCOPE_PO_ORDER
        );
    }

    public static function PERMISSION_LIST_ALL_PO_ORDER()
    {
        return self::createPermission(
            'list_all_po_distributor',
            'authentication.permissions.list_all_po_distributor.name',
            'authentication.permissions.list_all_po_distributor.description',
            PERMISSION_SCOPE_PO_ORDER
        );
    }

    public static function PERMISSION_LIST_PO_FROM_FACTORY_ORDER()
    {
        return self::createPermission(
            'list_po_from_factory_distributor',
            'authentication.permissions.list_po_from_factory_distributor.name',
            'authentication.permissions.list_po_from_factory_distributor.description',
            PERMISSION_SCOPE_PO_ORDER
        );
    }

    public static function PERMISSION_CREATE_PO_ORDER()
    {
        return self::createPermission(
            'create_po_distributor',
            'authentication.permissions.create_po_distributor.name',
            'authentication.permissions.create_po_distributor.description',
            PERMISSION_SCOPE_PO_ORDER
        );
    }

    public static function PERMISSION_EDIT_PO_ORDER()
    {
        return self::createPermission(
            'edit_po_distributor',
            'authentication.permissions.edit_po_distributor.name',
            'authentication.permissions.edit_po_distributor.description',
            PERMISSION_SCOPE_PO_ORDER
        );
    }

    public static function PERMISSION_DELETE_PO_ORDER()
    {
        return self::createPermission(
            'delete_po_distributor',
            'authentication.permissions.delete_po_distributor.name',
            'authentication.permissions.delete_po_distributor.description',
            PERMISSION_SCOPE_PO_ORDER
        );
    }

    /** ---- SO ---- */
    public static function PERMISSION_LIST_ALL_SO_ORDER()
    {
        return self::createPermission(
            'list_all_so_distributor',
            'authentication.permissions.list_all_so_distributor.name',
            'authentication.permissions.list_all_so_distributor.description',
            PERMISSION_SCOPE_SO_ORDER
        );
    }

    public static function PERMISSION_LIST_SO_FROM_FACTORY_ORDER()
    {
        return self::createPermission(
            'list_so_from_factory_distributor',
            'authentication.permissions.list_so_from_factory_distributor.name',
            'authentication.permissions.list_so_from_factory_distributor.description',
            PERMISSION_SCOPE_SO_ORDER
        );
    }

    public static function PERMISSION_CREATE_SO_ORDER()
    {
        return self::createPermission(
            'create_so_distributor',
            'authentication.permissions.create_so_distributor.name',
            'authentication.permissions.create_so_distributor.description',
            PERMISSION_SCOPE_SO_ORDER
        );
    }

    public static function PERMISSION_EDIT_SO_ORDER()
    {
        return self::createPermission(
            'edit_so_distributor',
            'authentication.permissions.edit_so_distributor.name',
            'authentication.permissions.edit_so_distributor.description',
            PERMISSION_SCOPE_SO_ORDER
        );
    }

    public static function PERMISSION_DELETE_SO_ORDER()
    {
        return self::createPermission(
            'delete_so_distributor',
            'authentication.permissions.delete_so_distributor.name',
            'authentication.permissions.delete_so_distributor.description',
            PERMISSION_SCOPE_SO_ORDER
        );
    }

    /** ---- DN ---- */
    public static function PERMISSION_LIST_ALL_DN_ORDER()
    {
        return self::createPermission(
            'list_all_dn_distributor',
            'authentication.permissions.list_all_dn_distributor.name',
            'authentication.permissions.list_all_dn_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }

    public static function PERMISSION_LIST_DN_FROM_FACTORY_ORDER()
    {
        return self::createPermission(
            'list_dn_from_factory_distributor',
            'authentication.permissions.list_dn_from_factory_distributor.name',
            'authentication.permissions.list_dn_from_factory_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }

    public static function PERMISSION_CREATE_DN_ORDER()
    {
        return self::createPermission(
            'create_dn_distributor',
            'authentication.permissions.create_dn_distributor.name',
            'authentication.permissions.create_dn_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }

    public static function PERMISSION_EDIT_DN_ORDER()
    {
        return self::createPermission(
            'edit_dn_distributor',
            'authentication.permissions.edit_dn_distributor.name',
            'authentication.permissions.edit_dn_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }

    public static function PERMISSION_DELETE_DN_ORDER()
    {
        return self::createPermission(
            'delete_dn_distributor',
            'authentication.permissions.delete_dn_distributor.name',
            'authentication.permissions.delete_dn_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }

    public static function PERMISSION_UPDATE_DN_ORDER() {
        return self::createPermission(
            'update_dn_distributor',
            'authentication.permissions.update_dn_distributor.name',
            'authentication.permissions.update_dn_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }

    public static function PERMISSION_CONFIRM_UPDATE_DN_ORDER() {
        return self::createPermission(
            'confirm_update_dn_distributor',
            'authentication.permissions.confirm_update_dn_distributor.name',
            'authentication.permissions.confirm_update_dn_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }

    public static function PERMISSION_REVERSE_DN_ORDER() {
        return self::createPermission(
            'reverse_dn_distributor',
            'authentication.permissions.reverse_dn_distributor.name',
            'authentication.permissions.reverse_dn_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }

    public static function PERMISSION_APPROVE_DN_ORDER() {
        return  self::createPermission(
            'approve_dn_distributor',
            'authentication.permissions.approve_dn_distributor.name',
            'authentication.permissions.approve_dn_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }


    public static function PERMISSION_REJECT_DN_ORDER() {
        return self::createPermission(
            'reject_dn_distributor',
            'authentication.permissions.reject_dn_distributor.name',
            'authentication.permissions.reject_dn_distributor.description',
            PERMISSION_SCOPE_DN_ORDER
        );
    }
}
