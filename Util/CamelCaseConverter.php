<?php

namespace NT\RestBundle\Util;


class CamelCaseConverter
{
    public static function camelToUnderscore($value)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $value));
    }

    public static function underscoreToLower($value)
    {
        return preg_replace('/\_(.)/e', "strtoupper('\\1')", $value);
    }

    public static function convertArrayKeysToUnderscore(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[self::camelToUnderscore($key)] = self::convertArrayKeysToUnderscore($value);
            } else {
                $result[self::camelToUnderscore($key)] = $value;
            }
        }
        return $result;
    }

}