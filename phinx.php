<?php
require_once 'app/bootstrap.php';

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/app/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/app/database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'ares_migration_log',
        'default_environment' => 'production',
        'production' => [
            'adapter' => 'mysql',
            "connection" => $capsule->getConnection('default')->getPdo(),
            "name" => $_ENV['DB_NAME'],
            'charset' => 'utf8'
        ],
    ],
    'version_order' => 'creation'
];
