const domain = document.domain.indexOf('localhost') !== -1 ? 'e-ordering.local' : document.domain;
const protocol = location.protocol;
export const constant: any = {
    BASE_WEB: protocol + '//' + domain + '/',
    API_VERSION: 'api/v1',
    BASE_API: protocol + '//' + domain + '/api/',
    FILE_UPLOAD: protocol + '//' + domain + '/files/upload/',
    FILE_MEDIA_BASE: protocol + '//' + domain + '/files/media/base/',
    BASE_FILE: protocol + '//' + domain + '/files/',
    FILE_MEDIA_THUMB: protocol + '//' + domain + '/files/media/thumb/',
    FILE_UPLOAD_IMAGE: protocol + '//' + domain + '/files/',
    DATE_FORMAT: 'dd-MM-yyyy',
    SESSION_TIMEOUT: 30, // minutes
    // User group
    ORDER_VIEWER: 2,
    ORDER_MANAGER: 3,
    GROUP_ADMIN: 1,
    GROUP_USER: 2,

    User: {
        group: {
            1: 'Group admin',
            2: 'Group member',
        },
        gender: {
            1: 'Male',
            2: 'Female',
        },
    },

    // tslint:disable-next-line:comment-format
    //Status Order
    WAITING_FOR_CONFIRM_ORDER: 1,
    REVIEWING_ORDER: 2,
    CLOSED_ORDER: 3,
    REJECT_BY_SALES_ORDER: 4,
    CANCELLED_BY_CUSTOMER_ORDER: 5,
    SALES_ACCEPTED_ORDER: 6,
    DELIVERING_ORDER: 7,
    CUSTOMER_SUBMITED_ORDER: 8,
    WAITING_FOR_DRAF_ORDER: 9,
    // Status Order Item
    WAITING_FOR_DRAF_ITEM_ORDER: 0,
    Accept_ITEM_ORDER: 1,
    REJECT_ITEM_ORDER: 2,
    // Type Order
    Type_Manual: 1,
    Type_Auto : 2,
    // System category
    CATEGORY_CODE1: 1,
    CATEGORY_CODE2: 2,
    CATEGORY_CODE3: 3,
    CATEGORY_CODE4: 4,

    LIMIT_USER_MANAGEMENT: 20,
    ORDER_ASC: 'ASC',
    ORDER_DESC: 'DESC',

    CODE_PRIME : 'f6fe8cca1e4f86a4de9cf353163dc36c',

    // System post
    POST_CODE1: 1,
    POST_CODE2: 2,
    POST_CODE3: 3,
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
    // attribute type
    Attributes_Type_List: 1,
    Attributes_Type_Number: 2,
    Attributes_Type_String: 3,
    // status sale item
    SO_DRAFT : 0,
    SO_OPEN : 1,
    SO_CLOSE: 2,

    // Post
    LIST_CATEGORY_FOR_POST: 1,
    LIST_POST_FOR_MENU: 2,

    // Menu type
    MENU_TYPE_NONE: 0,
    MENU_TYPE_INTERNAL: 1,
    MENU_TYPE_EXTERNAL: 2,

    TRANSLATION_TYPE_TEXT: 1,
    TRANSLATION_TYPE_MSG: 2,

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

    INIT_PASS_FLAG_TRUE: 1,
    INIT_PASS_FLAG_FALSE: 0,

    WAS_LOGGED_IN: 'was_logged_in',
    SESSION_EXPIRED: 'expired',
    FAKE_ORDER_ID_WHEN_CREATING: 'NEW'
};
