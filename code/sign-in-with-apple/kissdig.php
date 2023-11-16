<?php

declare(strict_types=1);

use Kissdigitalcom\AppleSignIn\ClientSecret;

require 'vendor/autoload.php';

require 'config.php';
require 'request.php';


$clientSecret = new ClientSecret($clientId, $teamId, $keyId, $certPath);

var_dump($jwt = $clientSecret->ttl(3600)->generate());

var_dump(request($clientId, $token, $jwt));