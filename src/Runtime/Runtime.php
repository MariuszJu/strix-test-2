<?php

namespace RockSolidSoftware\StrixTest\Runtime;

use RockSolidSoftware\StrixTest\Util\Config;
use RockSolidSoftware\StrixTest\Console\Console;
use RockSolidSoftware\StrixTest\Request\Request;

final class Runtime
{

    /**
     * @throws \Exception
     * @return Request
     */
    public static function request()
    {
        return App::make(Request::class);
    }

    /**
     * @return bool
     */
    public static function isCommandLineInterface()
    {
        if (strcasecmp(php_sapi_name(), 'cli') === 0) {
            return true;
        }
        if (defined('STDIN')) {
            return true;
        }
        if (array_key_exists('SHELL', $_ENV)) {
            return true;
        }
        if (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0) {
            return true;
        }
        if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public static function sapiName()
    {
        return strtolower(php_sapi_name());
    }

    /**
     * @return bool
     */
    public static function isWindows()
    {
        return strpos(self::os(), 'win') !== false;
    }

    /**
     * @return bool
     */
    public static function isLinux()
    {
        return strpos(self::os(), 'linux') !== false;
    }

    /**
     * @return string
     */
    public static function os()
    {
        return strtolower(PHP_OS);
    }

    /**
     * @throws \Exception
     * @return Config
     */
    public static function config()
    {
        return App::make(Config::class);
    }

    /**
     * @throws \Exception
     * @return Console
     */
    public static function console()
    {
        if (!self::isCommandLineInterface()) {
            throw new \Exception('Could not instantiate Console class when not in CLI!');
        }

        return App::make(Console::class);
    }

    /**
     * @return int
     */
    public static function maxUploadFileSize()
    {
        return max((int) ini_get('post_max_size'), (int) ini_get('upload_max_filesize'));
    }

    /**
     * @throws \Exception
     * @return bool
     */
    public static function debug()
    {
        if ($setting = DatabaseHelper::table('tblconfiguration')
            ->where('setting', 'LogErrors')
            ->first()
        ) {
            $setting = (array) $setting;

            return $setting['value'] == 'on';
        }

        return self::config()->configKey('debug', false);
    }

}
