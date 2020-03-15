<?php

/* --- System Group --- */
if (!defined('GROUP_ADMIN')) { define('GROUP_ADMIN', 1); } // Default
if (!defined('GROUP_MEMBER')) { define('GROUP_MEMBER', 2); }

/* --- Expire action time (minute) --- */
if (!defined('EXPIRE_SESSION_TIME')) { define('EXPIRE_SESSION_TIME', 30); }

/* --- Define Customer, User model --- */
if (!defined('ME')) { define('ME', 'me'); }

return [
    'User'=> [
        'gender'=>[0=>trans('Female'),1=>trans('Male')],
        'group'=> [
            GROUP_ADMIN => trans('Group admin'),
            GROUP_MEMBER => trans('Group member'),
        ]
    ]
];
?>
