<?php

declare(strict_types=1);

require 'vendor/autoload.php';

require 'config.php';
require 'request.php';

function encode($data)
{
    $encoded = strtr(base64_encode($data), '+/', '-_');
    return rtrim($encoded, '=');
}


function fromDER(string $der, int $partLength)
{
    $hex = unpack('H*', $der)[1];
    if ('30' !== mb_substr($hex, 0, 2, '8bit')) { // SEQUENCE
        throw new \RuntimeException();
    }
    if ('81' === mb_substr($hex, 2, 2, '8bit')) { // LENGTH > 128
        $hex = mb_substr($hex, 6, null, '8bit');
    } else {
        $hex = mb_substr($hex, 4, null, '8bit');
    }
    if ('02' !== mb_substr($hex, 0, 2, '8bit')) { // INTEGER
        throw new \RuntimeException();
    }
    $Rl = hexdec(mb_substr($hex, 2, 2, '8bit'));
    $R = retrievePositiveInteger(mb_substr($hex, 4, $Rl * 2, '8bit'));
    $R = str_pad($R, $partLength, '0', STR_PAD_LEFT);
    $hex = mb_substr($hex, 4 + $Rl * 2, null, '8bit');
    if ('02' !== mb_substr($hex, 0, 2, '8bit')) { // INTEGER
        throw new \RuntimeException();
    }
    $Sl = hexdec(mb_substr($hex, 2, 2, '8bit'));
    $S = retrievePositiveInteger(mb_substr($hex, 4, $Sl * 2, '8bit'));
    $S = str_pad($S, $partLength, '0', STR_PAD_LEFT);
    return pack('H*', $R . $S);
}

function preparePositiveInteger(string $data)
{
    if (mb_substr($data, 0, 2, '8bit') > '7f') {
        return '00' . $data;
    }
    while ('00' === mb_substr($data, 0, 2, '8bit') && mb_substr($data, 2, 2, '8bit') <= '7f') {
        $data = mb_substr($data, 2, null, '8bit');
    }
    return $data;
}

function retrievePositiveInteger(string $data)
{
    while ('00' === mb_substr($data, 0, 2, '8bit') && mb_substr($data, 2, 2, '8bit') > '7f') {
        $data = mb_substr($data, 2, null, '8bit');
    }
    return $data;
}

function generateJWT($kid, $iss, $sub, $path)
{
    $header = [
        'alg' => 'ES256',
        'kid' => $kid
    ];
    $body = [
        'iss' => $iss,
        'iat' => time(),
        'exp' => time() + 86400 * 90,
        'aud' => 'https://appleid.apple.com',
        'sub' => $sub
    ];

    $privKey = openssl_pkey_get_private(file_get_contents($path));
    if (!$privKey) {
        return false;
    }

    $payload = encode(json_encode($header)) . '.' . encode(json_encode($body));

    $signature = '';
    $success = openssl_sign($payload, $signature, $privKey, OPENSSL_ALGO_SHA256);
    if (!$success) {
        return false;
    }

    $raw_signature = fromDER($signature, 64);

    return $payload . '.' . encode($raw_signature);
}


$jwt = generateJWT($keyId, $teamId, $clientId, $certPath);
var_dump($jwt);

var_dump(request($clientId, $token, $jwt));
