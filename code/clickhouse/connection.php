<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';
//default
//$config = [
//    'host' => 'clickhouse-server',
//    'port' => '8123',
//    'username' => 'default',
//    'password' => '',
//];
$config = [
    'host' => 'clickhouse-server',
    'port' => '8123',
    'username' => 'user',
    'password' => 'secret',
];
$db = new ClickHouseDB\Client($config);
$db->database('test_database');
$db->setTimeout(1.5);      // 1 second , support only Int value
$db->setTimeout(10);       // 10 seconds
$db->setConnectTimeOut(5); // 5 seconds
$db->ping(true);

