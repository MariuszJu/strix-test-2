<?php

namespace RockSolidSoftware\StrixTest\Runtime;

use RockSolidSoftware\StrixTest\Console\AbstractCommand;
use ReflectionType;
use ReflectionClass;
use RockSolidSoftware\StrixTest\Util\HttpHelper;

final class App
{

    /** @var $this */
    private static $instance;

    /** @var array */
    private static $objects;

    private function __clone() {}
    private function __construct() {}
    private function __wakeup() {}

    /**
     * @throws \Exception
     * @param string $class
     * @return object
     */
    public static function singleton($class)
    {
        if (isset(self::$objects[$class])) {
            return self::$objects[$class];
        }

        $object = self::make($class);

        self::$objects[$class] = $object;

        return $object;
    }

    /**
     * @throws \Exception
     * @param string $class
     * @return object|void
     */
    public static function make($class)
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw new \Exception(sprintf('Class %s does not exist', $class));
        }

        $parameters = [];
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if ($constructor) {
            foreach ($constructor->getParameters() as $parameter) {
                $parameterClass = $parameter->getClass();

                if ($parameterClass instanceof ReflectionClass) {
                    $parameters[] = self::make($parameterClass->getName());
                } else {
                    if ($parameter->isDefaultValueAvailable()) {
                        $parameters[] = $parameter->getDefaultValue();
                        continue;
                    }

                    $parameterType = null;

                    if (method_exists($parameter, 'getType')) {
                        $parameterType = $parameter->getType();
                    }

                    if ($parameterType instanceof ReflectionType) {
                        $parameters[] = self::generateParameterValue($parameterType->__toString());
                        continue;
                    }

                    $parameters[] = null;
                }
            }
        }

        if (!interface_exists($reflection->name)) {
            $object = $reflection->newInstanceArgs($parameters);

            return $object;
        }
    }

    /**
     * @param array $argv
     */
    public static function init(array $argv = [])
    {
        try {
            if (Runtime::isCommandLineInterface()) {
                $console = Runtime::console();

                foreach (Runtime::config()->configKey('commands', []) as $commandClass) {
                    if (($command = App::make($commandClass)) instanceof AbstractCommand) {
                        $console->registerCommand($command);
                    }
                }

                $inputArgs = isset($_SERVER['argv']) ? $_SERVER['argv'] : $argv;
                $args = count($inputArgs) > 1 ? array_slice($inputArgs, 1) : [];

                empty($args)
                    ? $console->printCommands()
                    : $console->runCommand($args[0], array_slice($args, 1));

                return;
            }

            $url = HttpHelper::getFullUrl();
            $uri = str_replace([Runtime::config()->configKey('system_url'), '?'], '', $url);

            $routes = Runtime::config()->configKey('routes', []);
            
            foreach ($routes as $route) {
                if (!isset($route['route']) || !isset($route['action'])) {
                    continue;
                }

                if ($route['route'] == $uri) {
                    $matchedRoute = $route;
                    break;
                }
                if (preg_match(sprintf('/^%s$/', $route['route']), $uri)) {
                    $matchedRoute = $route;
                    break;
                }
            }

            if (!isset($matchedRoute)) {
                throw new \RuntimeException('Could not find any route for URL: '. $uri);
            }

            $action = $matchedRoute['action'];
            $parts = explode('@', $action);

            $controller = $parts[0];
            $action = $parts[1];

            if (!method_exists($controller, $action)) {
                throw new \RuntimeException(
                    sprintf('Controller %s does not have method %s', $controller, $action)
                );
            }

            App::make($controller)->$action();
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Generate default parameter by type
     *
     * @param string $type
     * @return array|float|int|null|string
     */
    private static function generateParameterValue($type)
    {
        switch ($type) {
            case 'int':
                return 1;
            case 'float':
                return 1.0;
            case 'array':
                return [];
            case 'string':
                return '';
            case 'bool':
                return true;
            default:
                return null;
        }
    }

}
