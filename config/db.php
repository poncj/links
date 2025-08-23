<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => ($_ENV['DB_DRIVER'] ?? 'mysql')
        . ':host=' . ($_ENV['DB_HOST'] ?? '127.0.0.1')
        . ';port=' . ($_ENV['DB_PORT'] ?? '3306')
        . ';dbname=' . ($_ENV['DB_NAME'] ?? 'links'),
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset' => 'utf8',
];