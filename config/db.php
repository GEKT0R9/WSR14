<?php
$DATABASE_URL = [
    'host' => 'localhost',
    'path' => '/yii_ldbt',
    'user' => 'postgres',
    'pass' => '',
];

$DATABASE_URL = parse_url(getenv("DATABASE_URL"));

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host='.$DATABASE_URL["host"].';dbname='.ltrim($DATABASE_URL["path"], "/"),
    'username' => $DATABASE_URL["user"],
    'password' => $DATABASE_URL["pass"],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
