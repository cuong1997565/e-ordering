const domain = document.domain == 'localhost' ? 'e-ordering.local' : document.domain;
const protocol = location.protocol;

export const constant: any = {
    BASE_WEB: protocol + '//' + domain + '/',
    BASE_API: protocol + '//' + domain + '/api/',
    FILE_UPLOAD: protocol + '//' + domain + '/files/upload/',
    FILE_MEDIA_BASE: protocol + '//' + domain + '/files/media/base/',
    FILE_MEDIA_THUMB: protocol + '//' + domain + '/files/media/thumb/',
    FILE_UPLOAD_IMAGE: protocol + '//' + domain + '/files/',

    DATE_FORMAT: 'dd-MM-yyyy',

    // Attributes type
    Attributes: {
        type : {
            1 : 'List',
            2 : 'Number',
            3 : 'String'
        }
    },
    Attributes_Type_List: 1,
    Attributes_Type_Number: 2,
    Attributes_Type_String: 3,

    YES: 1,
    NO: 0,

    Discount_Percent : 1,
    Discount_Fixed : 2,

    Discount_Type: {
       1 : 'Percentage',
       2 : 'Custom Rate',
       3 : 'Stack Discount'
    },

    Discount: {
        1: 'Percent',
        2: 'Fixed'
    },

    // So_status
    SO_DRAFT: 0,
    SO_OPEN: 1,
    SO_CLOSE: 2,
    // end

    // code prime
    CODE_PRIME : 'f6fe8cca1e4f86a4de9cf353163dc36c',
    // end

    // DN_STATUS
    deliveryDraftStatus : 0,
    deliveryConfirmStatus : 1,
    deliveryReverseStatus: 2,
    deliveryWaitingApproveWhenOver : 4,
    deliveryApproved : 5,
    deliveryReject : 6,


    ACTIVE_TRUE: 1,
    ACTIVE_FALSE: 0,
    IS_BASED_UOM_TRUE: 1,

    // User group
    GROUP_ADMIN: 1,
    ACCOUNT_HOLDER: 1,
    GROUP_CUSTOMER: 2,
    GROUP_SALE_FACTORY: 3,

    User: {
        group: {
            1: 'Group admin',
            2: 'Group customer',
            3: 'Group sale factory',
        },
        gender: {
            1: 'Male',
            2: 'Female',
        },
        member: {
            1: 'Account Holder',
            2: ''
        }
    },

    // System category
    CATEGORY_CODE1: 1,
    CATEGORY_CODE2: 2,
    CATEGORY_CODE3: 3,
    CATEGORY_CODE4: 4,

    // System post
    POST_CODE1: 1,
    POST_CODE2: 2,
    POST_CODE3: 3,

    // limit user
    LIMIT_USER_MANAGEMENT: 20,
    ORDER_ASC: 'ASC',
    ORDER_DESC: 'DESC',
    DEFAULT_RANKING_USER: 0,

    PRICE_DEFAULT_TRUE: 1,

    // order item
    STATUS_ACCEPT_ORDER_ITEM : 1,

    STATUS_REJECT_ORDER_ITEM : 2,

    Category: {
        ui_list_style: {
            1: 'Thumbnail on left',
            2: 'Thumbnail on top',
        },
        ui_list_col: { // More support on a style
            1: { // Style 1 has 2 version
                1: '1 column',
                2: '2 columns',
            },
            2: {  // Style 1 has 3 version
                3: '3 columns',
                4: '4 columns',
                5: '5 columns',
            },
        },
        ui_home_display: {
            1: 'Newest',
            2: 'Most viewed',
            3: 'Order in category'
        },
    },

    // Post
    LIST_CATEGORY_FOR_POST: 1,
    LIST_POST_FOR_MENU: 2,

    // Menu type
    MENU_TYPE_NONE: 0,
    MENU_TYPE_INTERNAL: 1,
    MENU_TYPE_EXTERNAL: 2,

    TRANSLATION_TYPE_TEXT: 1,
    TRANSLATION_TYPE_MSG: 2,

    TransactionTypeDR : 'dr',
    TransactionTypeCR : 'cr',

    // ------------ dashboard_status
    // po
    Po_Submit: 'Po_Submit',
    Po_Accept: 'Po_Accept',
    Po_Expired: 'Po_Expired',
    Po_Closed: 'Po_Closed',
    // end po

    // so
    So_Draft: 'So_Draft',
    So_Expired: 'So_Expired',
    So_Confirmed: 'So_Confirmed',
    So_Closed: 'So_Closed',
    // end so

    // dn
    Dn_Draft: 'Dn_Draft',
    Dn_WaitingForConfirm: 'Dn_WaitingForConfirm',
    Dn_Approve: 'Dn_Approve',
    Dn_Confirm: 'Dn_Confirm',

    // credit_limit_expired
    Credit_Limit_Expired: 'cl_expired',
    Credit_Limit_Upcoming_Expired: 'cl_upcoming_expired',
    // end ------------- dashboard_status

    Menu: {
        type: {
            0: 'None',
            1: 'Internal',
            2: 'External url'
        },
        type_internal:
            [
                // Special => Using the id of that table
                {'id': 1, 'name': 'Category'},
                {'id': 2, 'name': 'Post'},

                // General => Using the link
                {'id': 11, 'name': 'Contact', 'link': '/contact'},
                {'id': 12, 'name': 'FAQ', 'link': '/faq'},
            ],
        bind_post: { // Bind the post of category to this menu
            0: 'No',
            1: 'Yes',
        },
    },

    Unit: {
        1: 'brick',
        2: 'box',
        3: 'm²',
    },

    Unit_brick: 1,

    Translation: {
        lang: {
            1: 'vn',
            2: 'jp',
            3: 'cn',
            4: 'kr'
        },
        type: {
            1: 'Text',
            2: 'Msg'
        }
    },

    Active: {
        1: 'Yes',
        0: 'No'
    },
    TransactionType: {
       'dr': 'DR',
       'cr': 'CR'
    },
    Status: {
        1: 'Waiting for confirm',
        2: 'Reviewing',
        3: 'Closed',
        4: 'Rejected by Sales',
        5: 'Cancelled',
        6: 'Sales  Accepted (Locked)',
        7: 'Delivering',
        8: 'Submited',
    },
    StatusSale: {
        0: 'Draft',
        1: 'Open',
        2: 'Closed'
    },
    // type discount dn
    Type_Assign_discount_amount : 2,
    Type_Assign_discount_rate : 1,
    // end type discount dn
    // Status
    WAITING_FOR_CONFIRM : 1,
    REVIEWING: 2,
    CLOSED: 3,
    REJECTED_BY_SALES: 4,
    CANCELLED_BY_CUSTOMER: 5,
    SALES_ACCEPTED: 6,
    DELIVERING: 7,
    SUBMITED: 8,
    // Status Item Order
    WAITING_FOR_DRAF_ITEM_ORDER: 0,
    Accept_ITEM_ORDER: 1,
    REJECT_ITEM_ORDER: 2,
    // Type Order
    Type_Order_Manual : 1,
    Type_Order_Auto : 2,

    INIT_PASS_FLAG_TRUE: 1,
    INIT_PASS_FLAG_FALSE: 0,

    // Permissions Price
    PERMISSION_LIST_PRICES: 'list_price',
    PERMISSION_VIEW_PRICE: 'view_price',
    PERMISSION_EDIT_PRICE: 'edit_price',
    PERMISSION_CREATE_PRICE: 'create_price',

    // Permissions Discount Type
    PERMISSION_LIST_DISCOUNTS_TYPE: 'list_discount_type',
    PERMISSION_EDIT_DISCOUNT_TYPE: 'edit_discount_type',
    PERMISSION_CREATE_DISCOUNT_TYPE: 'create_discount_type',

    // Permissions Credit Account
    PERMISSION_LIST_CREDIT_ACCOUNTS: 'list_credit_accounts',
    PERMISSION_VIEW_CREDIT_ACCOUNT: 'view_credit_account',
    PERMISSION_EDIT_CREDIT_ACCOUNT: 'edit_credit_account',
    PERMISSION_DELETE_CREDIT_ACCOUNT: 'delete_credit_account',
    PERMISSION_CREATE_CREDIT_ACCOUNT: 'create_credit_account',

    // Permissions Specifications
    PERMISSION_LIST_SPECIFICATIONS: 'list_specifications',
    PERMISSION_EDIT_SPECIFICATION: 'edit_specification',
    PERMISSION_DELETE_SPECIFICATION: 'delete_specification',
    PERMISSION_CREATE_SPECIFICATION: 'create_specification',

    // Permissions Factory
    PERMISSION_LIST_FACTORIES: 'list_factories',
    PERMISSION_EDIT_FACTORY: 'edit_factory',
    PERMISSION_DELETE_FACTORY: 'delete_factory',
    PERMISSION_CREATE_FACTORY: 'create_factory',

    // Permissions AREAS
    PERMISSION_LIST_AREAS: 'list_areas',
    PERMISSION_EDIT_AREA: 'edit_area',
    PERMISSION_DELETE_AREA: 'delete_area',
    PERMISSION_CREATE_AREA: 'create_area',

    // Permissions Categories
    PERMISSION_LIST_CATEGORIES: 'list_categories',
    PERMISSION_EDIT_CATEGORY: 'edit_category',
    PERMISSION_DELETE_CATEGORY: 'delete_category',
    PERMISSION_CREATE_CATEGORY: 'create_category',

    // Permissions Brands
    PERMISSION_LIST_BRANDS: 'list_brands',
    PERMISSION_EDIT_BRAND: 'edit_brand',
    PERMISSION_DELETE_BRAND: 'delete_brand',
    PERMISSION_CREATE_BRAND: 'create_brand',

    // Permissions STORES
    PERMISSION_LIST_STORES: 'list_stores',
    PERMISSION_EDIT_STORE: 'edit_store',
    PERMISSION_DELETE_STORE: 'delete_store',
    PERMISSION_CREATE_STORE: 'create_store',

    // Permissions PRODUCTS
    PERMISSION_LIST_PRODUCTS: 'list_products',
    PERMISSION_EDIT_PRODUCT: 'edit_product',
    PERMISSION_DELETE_PRODUCT: 'delete_product',
    PERMISSION_CREATE_PRODUCT: 'create_product',

    // Permissions ORDERS
    PERMISSION_LIST_ORDERS: 'list_orders',
    PERMISSION_EDIT_ORDER: 'edit_order',
    PERMISSION_DELETE_ORDER: 'delete_order',
    PERMISSION_CREATE_ORDER: 'create_order',
    PERMISSION_REJECT_ITEM_PRODUCT_ORDER: 'reject_item_product_order',
    PERMISSION_UPDATE_ORDER: 'update_order',
    PERMISSION_APPROVE_ORDER: 'approve_order',
    PERMISSION_REVIEW_ORDER: 'review_order',
    PERMISSION_REJECT_ORDER: 'reject_order',
    // SO_ORDER
    PERMISSION_LIST_ALL_SO_ORDER: 'list_all_so_distributor',
    PERMISSION_CREATE_SO_ORDER: 'create_so_distributor',
    PERMISSION_EDIT_SO_ORDER: 'edit_so_distributor',

    // DN_ORDER
    PERMISSION_LIST_ALL_DN_ORDER: 'list_all_dn_distributor',
    PERMISSION_CREATE_DN_ORDER: 'create_dn_distributor',
    PERMISSION_EDIT_DN_ORDER: 'edit_dn_distributor',
    PERMISSION_UPDATE_DN_ORDER: 'update_dn_distributor',
    PERMISSION_CONFIRM_UPDATE_DN_ORDER : 'confirm_update_dn_distributor',
    PERMISSION_REVERSE_DN_ORDER : 'reverse_dn_distributor',
    PERMISSION_APPROVE_DN_ORDER : 'approve_dn_distributor',
    PERMISSION_REJECT_DN_ORDER : 'reject_dn_distributor',

    /** ----------------------------- Master data ----------------------------------*/
    // Admin
    PERMISSION_LIST_ADMINS: 'list_admins',
    PERMISSION_VIEW_ADMIN: 'view_admin',
    PERMISSION_EDIT_ADMIN: 'edit_admin',
    PERMISSION_DELETE_ADMIN: 'delete_admin',
    PERMISSION_CREATE_ADMIN: 'create_admin',

    // Member
    PERMISSION_LIST_MEMBERS: 'list_members',
    PERMISSION_VIEW_MEMBER: 'view_member',
    PERMISSION_EDIT_MEMBER: 'edit_member',
    PERMISSION_DELETE_MEMBER: 'delete_member',
    PERMISSION_CREATE_MEMBER: 'create_member',

    // UOM
    PERMISSION_LIST_UOMS: 'list_uoms',
    PERMISSION_CREATE_UOM: 'create_uom',
    PERMISSION_EDIT_UOM: 'edit_uom',

    // FEATURE
    PERMISSION_LIST_FEATURES: 'list_features',
    PERMISSION_CREATE_FEATURE: 'create_feature',
    PERMISSION_EDIT_FEATURE: 'edit_feature',

    // FEATURE_ITEM
    PERMISSION_LIST_FEATURE_ITEMS: 'list_feature_items',
    PERMISSION_CREATE_FEATURE_ITEM: 'create_feature_item',
    PERMISSION_EDIT_FEATURE_ITEM: 'edit_feature_item',

    // ATTRIBUTE
    PERMISSION_LIST_ATTRIBUTES: 'list_attributes',
    PERMISSION_CREATE_ATTRIBUTE: 'create_attribute',
    PERMISSION_EDIT_ATTRIBUTE: 'edit_attribute',

    // ATTRIBUTE_LIST_OF_VALUE
    PERMISSION_LIST_ATTRIBUTE_LIST_OF_VALUES: 'list_attribute_list_of_values',
    PERMISSION_CREATE_ATTRIBUTE_LIST_OF_VALUE: 'create_attribute_list_of_value',
    PERMISSION_EDIT_ATTRIBUTE_LIST_OF_VALUE: 'edit_attribute_list_of_value',

    // GRADE
    PERMISSION_LIST_GRADES: 'list_grades',
    PERMISSION_CREATE_GRADE: 'create_grade',
    PERMISSION_EDIT_GRADE: 'edit_grade',

    // GRADE_GROUP
    PERMISSION_LIST_GRADE_GROUPS: 'list_grade_groups',
    PERMISSION_CREATE_GRADE_GROUP: 'create_grade_group',
    PERMISSION_EDIT_GRADE_GROUP: 'edit_grade_group',

    // UOM MULTIPLE
    PERMISSION_LIST_UOM_MULTIPLES: 'list_uom_multiples',
    PERMISSION_CREATE_UOM_MULTIPLE: 'create_uom_multiple',
    PERMISSION_EDIT_UOM_MULTIPLE: 'edit_uom_multiple',

    // PRODUCT TYPE
    PERMISSION_LIST_PRODUCT_TYPES: 'list_product_types',
    PERMISSION_CREATE_PRODUCT_TYPE: 'create_product_type',
    PERMISSION_EDIT_PRODUCT_TYPE: 'edit_product_type',

    // DISTRIBUTOR
    PERMISSION_LIST_DISTRIBUTORS: 'list_distributor',
    PERMISSION_CREATE_DISTRIBUTOR: 'create_distributor',
    PERMISSION_EDIT_DISTRIBUTOR: 'edit_distributor',
    PERMISSION_ADD_PRODUCT_DISTRIBUTOR: 'add_product_distributor',

    // ROLES
    PERMISSION_LIST_ROLES: 'list_roles',
    PERMISSION_CREATE_ROLE: 'create_role',
    PERMISSION_EDIT_ROLE: 'edit_role',

};
