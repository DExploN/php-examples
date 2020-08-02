<?php

declare(strict_types=1);

use \Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;

require 'vendor/autoload.php';

require 'config.php';
require 'request.php';

class JWTAppleEncoder extends JWT
{
    public static function encode($payload, $key, $alg = 'HS256', $keyId = null, $head = null)
    {
        // Изменена эта строка
        $header = array('alg' => $alg);
        if ($keyId !== null) {
            $header['kid'] = $keyId;
        }
        if (isset($head) && is_array($head)) {
            $header = array_merge($head, $header);
        }
        $segments = array();
        $segments[] = static::urlsafeB64Encode(static::jsonEncode($header));
        $segments[] = static::urlsafeB64Encode(static::jsonEncode($payload));
        $signing_input = implode('.', $segments);

        $signature = static::sign($signing_input, $key, $alg);
        $segments[] = static::urlsafeB64Encode($signature);

        return implode('.', $segments);
    }
}

$header = [
    'alg' => 'ES256',
    'kid' => $keyId
];
$payload = [
    'iss' => $teamId,
    'iat' => time(),
    'exp' => time() + 3600,
    'aud' => 'https://appleid.apple.com',
    'sub' => $clientId
];

//$jwt = JWTAppleEncoder::encode($payload, file_get_contents($certPath), 'ES256', $keyId,$header);
$jwt = JWT::encode($payload, file_get_contents($certPath), 'ES256', $keyId, $header);
var_dump($jwt);


try {
    $client = new Client();
    $response = $client->post(
        'https://appleid.apple.com/auth/token',
        [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => (string)$jwt,
                'code' => $token,
                'grant_type' => 'authorization_code'
            ],
            RequestOptions::TIMEOUT => 10,
            RequestOptions::CONNECT_TIMEOUT => 10,
            RequestOptions::READ_TIMEOUT => 10
        ]
    );
} catch (RequestException $exception) {
    var_dump($exception->getResponse()->getBody()->getContents());
    exit();
}


//var_dump(request($clientId, $token, $jwt));