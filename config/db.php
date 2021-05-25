<?php

if (getenv("YII_ENV") == 'prod') {
    $url = parse_url(getenv("DATABASE_URL"));
    $dsn = 'pgsql:host='.$url['host'].';dbname='.ltrim($DATABASE_URL["path"], "/");
    $username = $url["user"];
    $password = $url["pass"];
} else {
    $dsn = 'pgsql:host=localhost;dbname=yii_ldbt';
    $username = 'postgres';
    $password = '';
}

return [
    'class' => 'yii\db\Connection',
    'dsn' => $dsn,
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',
];
