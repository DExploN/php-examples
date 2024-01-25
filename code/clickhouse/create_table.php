<?php

declare(strict_types=1);

require_once 'connection.php';

assert(isset($db) &&  $db instanceof ClickHouseDB\Client);

$db->write('
    CREATE TABLE IF NOT EXISTS summing_url_views (
        event_date Date DEFAULT toDate(event_time),
        event_time DateTime,
        site_id Int32,
        site_key String,
        views Int32,
        v_00 Int32,
        v_55 Int64
    )
    ENGINE = SummingMergeTree((site_id,views))
    ORDER BY event_time
');
