<?php

namespace RockSolidSoftware\StrixTest\Util;

final class ArrayUtil
{

    private function __clone() {}
    private function __construct() {}
    private function __wakeup() {}

    /**
     * @param mixed $array
     * @return array
     */
    public static function wrapArray($array)
    {
        return is_array($array) ? $array : [$array];
    }

    /**
     * @param array $array
     * @return bool
     */
    public static function isMultiArray(array $array)
    {
        return (bool) (count($array) != count($array, COUNT_RECURSIVE));
    }

    /**
     * @param object $object
     * @return array
     */
    public static function arrayCopy($object)
    {
        $callback = [$object, 'getArrayCopy'];
        $array = is_callable($callback) ? call_user_func($callback) : get_object_vars($object);

        foreach ($array as $field => $value) {
            if (is_object($value) || is_array($value)) {
                $array[$field] = self::arrayCopy($value);
            }
        }

        return $array;
    }

    /**
     * @param mixed $value
     * @param array $array
     */
    public static function removeValue($value, array &$array)
    {
        if ($key = array_search($value, $array)) {
            unset($array[$key]);
        }
    }

    /**
     * @param mixed $key
     * @param array $array
     */
    public static function removeKey($key, array &$array)
    {
        if (array_key_exists($key, $array)) {
            unset($array[$key]);
        }
    }

}
