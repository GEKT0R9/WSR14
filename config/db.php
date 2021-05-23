<?php
$DATABASE_URL = [
    'host' => 'localhost',
    'path' => '/yii_ldbt',
    'user' => 'postgres',
    'pass' => '',
];
$url = "postgres://fexvodefzkeggz:29e87820ce50be122e986f71de56e95f964ac0fadc94d746947cee807f65430d@ec2-52-19-170-215.eu-west-1.compute.amazonaws.com:5432/d91r9ph6tifk9e";
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
