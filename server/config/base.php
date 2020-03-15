<?php

/* --- Basic constant --- */
if (!defined('MESSAGE')) {define('MESSAGE', 'message');}
if (!defined('MODEL_PATH')) {define('MODEL_PATH', '\App\Models\\');}
if (!defined('API_PREFIX')) {define('API_PREFIX', 'api');}
if (!defined('API_CLIENT_PREFIX')) {define('API_CLIENT_PREFIX', 'v1');}
if (!defined('SESSION_EXPIRED')) {define('SESSION_EXPIRED', 30);} // Expired in 30 minutes
if (!defined('CLIENT_SESSION_EXPIRED')) {define('CLIENT_SESSION_EXPIRED', 30);} // Expired in 30 minutes
if (!defined('EMAIL_EXPIRED')) {define('EMAIL_EXPIRED', 86400);} // Expired in 24 hours

/* --- System active */
if (!defined('ACTIVE_TRUE')) {define('ACTIVE_TRUE', 1);}
if (!defined('ACTIVE_FALSE')) {define('ACTIVE_FALSE', 0);}

/* ----System Group Admin ----- */
if (!defined('GROUP_ADMIN')) {define('GROUP_ADMIN', 1);}
if (!defined('GROUP_CUSTOMER')) {define('GROUP_CUSTOMER', 2);}
if (!defined('GROUP_SALE_FACTORY')) {define('GROUP_SALE_FACTORY', 3);}

/* --- Error type file --- */
if (!defined('FILE_ERROR_MAX_SIZE')) {define('FILE_ERROR_MAX_SIZE', 1);}
if (!defined('FILE_ERROR_EXTENSION')) {define('FILE_ERROR_EXTENSION', 2);}
if (!defined('FILE_ERROR_EMPTY')) {define('FILE_ERROR_EMPTY', 3);}

/* --- POST --- */
if (!defined('LIST_CATEGORY_FOR_POST')) {define('LIST_CATEGORY_FOR_POST', 1);}
if (!defined('LIST_POST_FOR_MENU')) {define('LIST_POST_FOR_MENU', 2);}

/* --- CATEGORY --- */
if (!defined('DEFAULT_CATEGORY_LEVEL')) {define('DEFAULT_CATEGORY_LEVEL', 1);}

/* --- MENU --- */
if (!defined('MENU_TYPE_NONE')) {define('MENU_TYPE_NONE', 0);}
if (!defined('MENU_TYPE_INTERNAL')) {define('MENU_TYPE_INTERNAL', 1);}
if (!defined('MENU_TYPE_EXTERNAL')) {define('MENU_TYPE_EXTERNAL', 2);}

if (!defined('MENU_TYPE_INTERNAL_CATEGORY')) {define('MENU_TYPE_INTERNAL_CATEGORY', 1);}
if (!defined('MENU_TYPE_INTERNAL_POST')) {define('MENU_TYPE_INTERNAL_POST', 2);}
if (!defined('MENU_TYPE_INTERNAL_CONTACT')) {define('MENU_TYPE_INTERNAL_CONTACT', 11);}
if (!defined('MENU_TYPE_INTERNAL_FAQ')) {define('MENU_TYPE_INTERNAL_FAQ', 12);}

/* --- Type Order --- */
if (!defined('TYPE_AUTO_ORDER')) {define('TYPE_AUTO_ORDER', 2);}

/* --- TRANSLATION --- */
if (!defined('TRANSLATION_VN')) {define('TRANSLATION_VN', 1);}
if (!defined('TRANSLATION_JP')) {define('TRANSLATION_JP', 2);}
if (!defined('TRANSLATION_CN')) {define('TRANSLATION_CN', 3);}
if (!defined('TRANSLATION_KR')) {define('TRANSLATION_KR', 4);}


if (!defined('TRANSLATION_TYPE_TEXT')) {define('TRANSLATION_TYPE_TEXT', 1);}
if (!defined('TRANSLATION_TYPE_MSG')) {define('TRANSLATION_TYPE_MSG', 2);}

if (!defined('LAST_ACTIVITY_TIMEOUT')) {define('LAST_ACTIVITY_TIMEOUT', 5);}

if (!defined('HEADER_ETAG_SERVER')) {define('HEADER_ETAG_SERVER', 'ETag');}
if (!defined('HEADER_ETAG_CLIENT')) {define('HEADER_ETAG_CLIENT', 'If-None-Match');}

/* ----- ORDER STATUS ----*/
if (!defined('WAITING_FOR_CONFIRM_ORDER')) {define('WAITING_FOR_CONFIRM_ORDER', 1);}
if (!defined('REVIEWING_ORDER')) {define('REVIEWING_ORDER', 2);} 
if (!defined('CLOSED_ORDER')) {define('CLOSED_ORDER', 3);}
if (!defined('REJECT_BY_SALES_ORDER')) {define('REJECT_BY_SALES_ORDER', 4);}
if (!defined('CANCELLED_BY_CUSTOMER_ORDER')) {define('CANCELLED_BY_CUSTOMER_ORDER', 5);}
if (!defined('SALES_ACCEPTED_ORDER')) {define('SALES_ACCEPTED_ORDER', 6);}
if (!defined('DELIVERING_ORDER')) {define('DELIVERING_ORDER', 7);}
if (!defined('SUBMITED_ORDER')) {define('SUBMITED_ORDER', 8);}
if (!defined('WAITING_FOR_DRAF_ORDER')) {define('WAITING_FOR_DRAF_ORDER', 9);}
if (!defined('SALES_CLOSE_ORDER')) {define('SALES_CLOSE_ORDER', 10);}


/* ----- ORDER ITEM STATUS ----*/
if (!defined('WAITING_FOR_DRAF_ITEM_ORDER')) {define('WAITING_FOR_DRAF_ITEM_ORDER', 0);}
if (!defined('Accept_ITEM_ORDER')) {define('Accept_ITEM_ORDER', 1);}
if (!defined('REJECT_ITEM_ORDER')) {define('REJECT_ITEM_ORDER', 2);}

/* ----- SO STATUS ----*/
if (!defined('SO_DRAFT_STATUS')) {define('SO_DRAFT_STATUS', 0);}
if (!defined('SO_OPEN_STATUS')) {define('SO_OPEN_STATUS', 1);}
if (!defined('SO_CLOSE_STATUS')) {define('SO_CLOSE_STATUS', 2);}

/* ----- SO ITEMS STATUS ---- */
if (!defined('SO_ITEM_DRAFT_STATUS')) {define('SO_ITEM_DRAFT_STATUS', 0);}
if (!defined('SO_ITEM_OPEN_STATUS')) {define('SO_ITEM_OPEN_STATUS', 1);}
if (!defined('SO_ITEM_CLOSE_STATUS')) {define('SO_ITEM_CLOSE_STATUS', 2);}

/* ----- Attributes Type ----*/
if (!defined('Attributes_Type_List')) {define('Attributes_Type_List', 1);}
if (!defined('Attributes_Type_Number')) {define('Attributes_Type_Number', 2);}
if (!defined('Attributes_Type_String')) {define('Attributes_Type_String', 3);}

/*--is_based_uom about table uom   */
if (!defined('IS_BASED_UOM_FALSE')) {define('IS_BASED_UOM_FALSE', 0);}
if (!defined('IS_BASED_UOM_TRUE')) {define('IS_BASED_UOM_TRUE', 1);}

/*--Default_Price_List   */
if (!defined('DEFAULT_PRICE_LIST')) {define('DEFAULT_PRICE_LIST', 1);}

/*-- Transaction -- */
if (!defined('TRANSACTION_TYPE_DR')) {define('TRANSACTION_TYPE_DR', 'dr');}
if (!defined('TRANSACTION_TYPE_CR')) {define('TRANSACTION_TYPE_CR', 'cr');}
if (!defined('TRANSACTION_TYPE_AUTO_DR_DESCRIPTION')) {define('TRANSACTION_TYPE_AUTO_DR_DESCRIPTION', 'Issued DN');}
if (!defined('TRANSACTION_TYPE_REVERT_DESCRIPTION')) {define('TRANSACTION_TYPE_REVERT_DESCRIPTION', 'Reversed DN');}


if (!defined('IS_ACCOUNT_HOLDER')) {define('IS_ACCOUNT_HOLDER', 1);}


return [
    'System'=>
    [
        'active'=> [
            ACTIVE_TRUE => trans('True'),
            ACTIVE_FALSE => trans('False')
        ],
        'uploadTmp' => 'temp',
        'file_error' => [
            FILE_ERROR_EMPTY => trans('This file is require'),
            FILE_ERROR_MAX_SIZE => trans('File size exceeds allowable'),
            FILE_ERROR_EXTENSION => trans('This file is not valid')
        ],
        'multiLangField' => [
            'title', 'description', 'content'
        ]
    ],

    'default_lang' => 'vn',

    'Translation' => [
        TRANSLATION_VN => 'vn',
        TRANSLATION_JP => 'jp',
        TRANSLATION_CN => 'cn',
        TRANSLATION_KR => 'kr'
    ]
];
?>
