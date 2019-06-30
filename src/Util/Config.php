<?php

namespace RockSolidSoftware\StrixTest\Util;

final class Config
{

    /**
     * @var array
     */
    private $config;

    private function __clone() {}
    private function __wakeup() {}

    /**
     * Config constructor
     */
    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * @return $this
     */
    private function loadConfig()
    {
        if (!empty($this->config)) {
            return $this;
        }

        $configFile = STRIX_TEST_ROOT_DIR . '/resources/config/config.php';
        
        if (!file_exists($configFile)) {
            $this->config = [];

            return $this;
        }

        $this->config = require $configFile;

        return $this;
    }

    /**
     * Get config by key
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function configKey($key, $default = null)
    {
        $keys = strpos($key, '.') !== false ? explode('.', $key) : [$key];

        $index = 0;
        $found = true;
        $currentConfig = $this->config;
        do {
            $key = $keys[$index++];

            if (is_array($currentConfig) && isset($currentConfig[$key])) {
                $currentConfig = $currentConfig[$key];
            } else {
                $found = false;
                break;
            }

        } while ($index < count($keys));

        return $found ? $currentConfig : $default;
    }

}
