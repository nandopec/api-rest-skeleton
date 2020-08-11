<?php
return [
    'basePath' => '/nandopec/api-rest-skeleton',
    'displayErrors' => false,
    'clientServer' => 'http://localhost:4200',
    // Loger settings
    'logger' => [
        'name' => 'app',
        'path' => __DIR__ . '/../logs',
        'filename' => 'app.log',
        'level' => \Monolog\Logger::DEBUG,
        'file_permission' => 0777,
    ],
    // Database config
    'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => '',
        'username' => '',
        'password' => '',
        'charset' => 'utf8',
        'colation' => 'utf8_general_ci',
        'prefix' => 'abc0820_'
    ]
];
?>
