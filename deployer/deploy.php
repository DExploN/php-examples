<?php

use function Deployer\get;
use function Deployer\inventory;
use function Deployer\parse;
use function Deployer\task;

require 'vendor/autoload.php';

inventory('hosts.yml');

task(
    "test",
    function () {
        var_dump(get("stage"));
    }
);
task(
    "test2",
    function () {
        echo 1;
    }
);