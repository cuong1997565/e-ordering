<?php
namespace App\Helpers;

use DateTimeInterface;

class Util {
    public static function Etag(...$params) {
        $string = '';
        foreach($params as $param) {
            $string .= '.'.$param;
        }
        return $string;
    }

    public static function validateInteger($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    public static function validateDate($value)
    {
        if ($value instanceof DateTimeInterface) {
            return true;
        }

        if ((! is_string($value) && ! is_numeric($value)) || strtotime($value) === false) {
            return false;
        }
        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }
}
