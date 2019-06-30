<?php

return [
    /**
     * Key used for encryption
     *
     * @string
     */
    'encryption_key' => 'two-minutes-to-midnight',

    /**
     * Database credentials
     *
     * @array
     */
    'database'  => [
        'user'    => 'root',
        'pass'    => '',
        'name'    => 'strix',
        'host'    => 'localhost',
        'adapter' => \RockSolidSoftware\StrixTest\Database\Adapter\PDO::class,
    ],

    /**
     * Full System URL
     *
     * @string
     */
    'system_url' => 'http://strix2.localhost/',

    /**
     * Routes collection
     *
     * @array
     */
    'routes' => include dirname(__DIR__) . '/routes/routes.php',

    /**
     * Console commands
     *
     * @array
     */
    'commands' => [
        \RockSolidSoftware\StrixTest\Console\TaskCommand::class,
    ],
];