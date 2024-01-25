<?php

declare(strict_types=1);

require_once 'connection.php';

use ClickHouseDB\Type\UInt64;

assert(isset($db) &&  $db instanceof ClickHouseDB\Client);

$stat = $db->insert('summing_url_views',
    [
        [time(), 'HASH1', 2345, 22, 20, 2],
        [time(), 'HASH2', 2345, 12, 9,  3],
        [time(), 'HASH3', 5345, 33, 33, 0],
        [time(), 'HASH3', 5345, 55, 0, 55],
    ],
    ['event_time', 'site_key', 'site_id', 'views', 'v_00', 'v_55']
);

$statement = $db->insert('summing_url_views',
    [
        [time(), UInt64::fromString('184467440737')],
    ],
    ['event_time', 'v_55']
);
