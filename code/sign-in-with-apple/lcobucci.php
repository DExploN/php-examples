<?php

declare(strict_types=1);

require 'vendor/autoload.php';

require 'config.php';
require 'request.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;

$signer = new Sha256();
$key = file_get_contents($certPath);

$privateKey = new Key($key);
$time = time();

$token = (new Builder())->issuedBy($teamId) // Configures the issuer (iss claim)
->withHeader('alg', 'ES256')
    ->withHeader('kid', $keyId)
    ->issuedAt($time) // Configures the time that the token was issue (iat claim)
    ->expiresAt($time + 1200) // Configures the expiration time of the token (exp claim)
    ->withClaim('aud', 'https://appleid.apple.com')
    ->withClaim('sub', $clientId)
    ->getToken($signer, $privateKey); // Re

$jwt = (string)$token;

var_dump($jwt);

var_dump(request($clientId, $token, $jwt));