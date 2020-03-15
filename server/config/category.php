<?php

/* --- Define list system */
if (!defined('CATEGORY_CODE1')) {define('CATEGORY_CODE1', 1);}
if (!defined('CATEGORY_CODE2')) {define('CATEGORY_CODE2', 2);}
if (!defined('CATEGORY_CODE3')) {define('CATEGORY_CODE3', 3);}

/* --- Define list system */
if (!defined('CATEGORY_STYLE_1')) {define('CATEGORY_STYLE_1', 1);} // Left
if (!defined('CATEGORY_STYLE_2')) {define('CATEGORY_STYLE_2', 2);} // Top

return [
    'sys'=> [ CATEGORY_CODE1, CATEGORY_CODE2, CATEGORY_CODE3 ],
    'style'=> [ CATEGORY_STYLE_1, CATEGORY_STYLE_2 ],
];

?>