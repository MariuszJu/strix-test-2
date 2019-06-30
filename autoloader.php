<?php

final class Autoloader
{

    /** @var array */
    private static $paths;
    
    /** @var string */
    private static $modulesPath;

    /** @var string */
    private static $baseNamespace = 'RockSolidSoftware\StrixTest';
    
    /** @var bool */
    private static $throwExceptions = true;

    /**
     * Init Autoloader
     */
    public static function init()
    {
        self::$paths[] = __DIR__ . '/src';
        self::$paths[] = __DIR__ . '/vendor/PHPThumb/src';

        spl_autoload_register('static::loadClass');
    }

    /**
     * Load given class
     *
     * @throws \Exception
     * @param string $class
     * @return void
     */
    protected static function loadClass($class)
    {
        if (($pos = strpos($class, self::$baseNamespace . '\\')) !== false) {
            $class = substr($class, $pos + strlen(self::$baseNamespace) + 1);
        }

        $relativeFilePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $class) . '.php';
        
        foreach (self::$paths as $path) {
            if (substr($path, -1, 1) != '/') {
                $path .= '/';
            }

            $filePath = realpath($path . $relativeFilePath);
            
            if (file_exists($filePath)) {
                require_once $filePath;
                return;
            }
        }

        if (self::$throwExceptions) {
            throw new \Exception(sprintf('Could not find %s class', $class));
        }
    }

}

Autoloader::init();