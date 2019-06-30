<?php

namespace RockSolidSoftware\StrixTest\Util;

use RockSolidSoftware\StrixTest\Runtime\Runtime;

final class Inflector
{

    private function __clone() {}
    private function __construct() {}
    private function __wakeup() {}
    
    /**
     * @param string $name
     * @return string
     */
    public static function createSlug(string $name)
    {
        if (empty($name)) {
            return '';
        }

        $slug = strtr($name, [
            'ą' => 'a',
            'ę' => 'e',
            'ś' => 's',
            'ć' => 'c',
            'ł' => 'l',
            'ó' => 'o',
            'ź' => 'z',
            'ż' => 'z',
            'ń' => 'n',
            'Ą' => 'a',
            'Ę' => 'e',
            'Ś' => 's',
            'Ć' => 'c',
            'Ł' => 'l',
            'Ó' => 'o',
            'Ź' => 'z',
            'Ż' => 'z',
            'Ń' => 'n',
        ]);

        $replaced = str_replace([' ', ',', '.', '*', '&', '!', '^', '%', '$', '#', '@', '(', ')', '\'', '"', '[', ']', ';', '?', '~', '`', ':', '+', '=', '/'], '-', $slug);
        $replaced = str_replace(['---', '--', ' ', '-'], '-', $replaced);

        if (in_array($replaced[strlen($replaced) - 1], ['-', '.', ',', ' '])) {
            $replaced = substr($replaced, 0, strlen($replaced) - 1);
        }
        
        return strtolower(preg_replace('/\s+/', ' ', $replaced));
    }
    
    /**
     * @param int  $length
     * @param bool $onlyLetters
     * @return bool|string
     */
    public static function randomString(int $length = 8, bool $onlyLetters = false)
    {
        $alphaNumeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . ($onlyLetters ? '' : '01234567890123456789');
        return substr(str_shuffle($alphaNumeric), 0, $length);
    }

    /**
     * @param int $length
     * @return string
     */
    public static function randomNumber(int $length = 8)
    {
        $digits = '012345678901234567890123456789012345678901234567890123456789';
        return substr(str_shuffle($digits), 0, $length);
    }
    
    /**
     * $mode = 1 - add random prefix (before) fileName, 2 - add random postfix (after) fileName
     *
     * @param string $name
     * @param int    $maxLength
     * @param int    $mode
     * @param int    $randomLength
     * @param string $separator
     * @return string;
     */
    public static function normalizeFileName(
        string $name, int $maxLength = 20, int $mode = 2, int $randomLength = 3, string $separator = '_')
    {
        $slug = self::createSlug($name);
        
        if (strlen($slug) > $maxLength) {
            $tmp = substr($slug, 0, 20);
        } else {
            $tmp = $slug;
        }
        
        $random = self::randomString($randomLength);
        
        if ($mode == 1) {
            $fileName = $random . $separator . $tmp;
        } else if ($mode == 2) {
            $fileName = $tmp . $separator . $random;
        }
        
        return $fileName;
    }
    
    /**
     * @param string $string
     * @param bool   $capitalizeFirstCharacter
     * @return mixed|string
     */
    public static function toCamelCase(string $string, bool $capitalizeFirstCharacter = false)
    {
        if (empty($string)) {
            return $string;
        }
        $str = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $string)));
        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        
        return $str;
    }
    
    /**
     * @param string $input
     * @param bool   $capitalizeFirstCharacter
     * @return string
     */
    public static function to_underscore(string $input, bool $capitalizeFirstCharacter = false)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        
        $result = implode('_', $ret);
        if ($capitalizeFirstCharacter) {
            return ucfirst($result);
        }
        
        return $result;
    }

    /**
     * Cut the text to given length and do not break words
     *
     * @param string $text
     * @param int    $length
     * @param string $postfix
     * @return string
     */
    public static function cutText(string $text, int $length, string $postfix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        $position = strpos($text, ' ', $length);

        if ($position === false) {
            return $text;
        }

        $cutted = substr($text,0,$position);

        if (strlen($cutted) != strlen($text) && !empty($postfix)) {
            return $cutted . $postfix;
        }

        return $cutted;
    }

    /**
     * @param string $content
     * @return array
     */
    public static function explodeByNewLine(string $content)
    {
        return preg_split('/\r\n|\r|\n/', $content);
    }

    /**
     * @throws \Exception
     * @param string $plainText
     * @return string
     */
    public static function encrypt(string $plainText)
    {
        if (!function_exists('openssl_encrypt')) {
            return base64_encode($plainText);
        }

        $encryption = Runtime::config()->configKey('encryption_key');
        $ivlen = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
        $iv = openssl_random_pseudo_bytes($ivlen);
        $cipherTextRaw = openssl_encrypt($plainText, $cipher, $encryption, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $cipherTextRaw, $encryption, true);

        return base64_encode($iv.$hmac.$cipherTextRaw);
    }

    /**
     * @throws \Exception
     * @param string $encrypted
     * @return array|null
     */
    public static function decrypt(string $encrypted)
    {
        if (!function_exists('openssl_decrypt')) {
            return base64_decode($encrypted);
        }

        $encryption = Runtime::config()->configKey('encryption_key');
        $c = base64_decode($encrypted);
        $ivlen = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $cipherTextRaw = substr($c, $ivlen + $sha2len);
        $originalPlainText = openssl_decrypt($cipherTextRaw, $cipher, $encryption, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $cipherTextRaw, $encryption, true);

        if (hash_equals($hmac, $calcmac)) {
            return $originalPlainText;
        }

        return null;
    }

    /**
     * @param string $string
     * @param string $char
     * @return string
     */
    public static function trimLastChar(string $string, string $char)
    {
        if (substr($string, -1, 1) == $char) {
            $string = substr($string, 0, -1);
        }

        return $string;
    }

    /**
     * @param string $string
     * @param string $char
     * @return string
     */
    public static function trimFirstChar(string $string, string $char)
    {
        return substr($string, 0, 1) == $char ? substr($string, 1) : $string;
    }


}
