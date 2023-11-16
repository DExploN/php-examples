<?php

//https://gist.github.com/patrickbussmann/877008231ef082cc5dc4ee5ca661a641
declare(strict_types=1);

require_once 'vendor/autoload.php';

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;

require 'config.php';

/** Your team identifier: https://developer.apple.com/account/#/membership/ (Team ID) */
$teamId = $teamId;
/** The client id of your service: https://developer.apple.com/account/resources/identifiers/list/serviceId */
$clientId = $clientId;
/** Code from request: https://appleid.apple.com/auth/authorize?response_type=code&client_id={$clientId}&scope=email%20name&response_mode=form_post&redirect_uri={$redirectUri} */
$code = $token;
/** The ID of the key file: https://developer.apple.com/account/resources/authkeys/list (Key ID) */
$keyFileId = $keyId;
/** The path of the file which you downloaded from https://developer.apple.com/account/resources/authkeys/list */
$keyFileName = $certPath;
/** The redirect uri of your service which you used in the $code request */
$redirectUri = 'https://example.org';

$algorithmManager = new AlgorithmManager([new ES256()]);

$jwsBuilder = new JWSBuilder($algorithmManager);
$jws = $jwsBuilder
    ->create()
    ->withPayload(
        json_encode(
            [
                'iat' => time(),
                'exp' => time() + 3600,
                'iss' => $teamId,
                'aud' => 'https://appleid.apple.com',
                'sub' => $clientId
            ]
        )
    )
    ->addSignature(
        JWKFactory::createFromKeyFile($keyFileName),
        [
            'alg' => 'ES256',
            'kid' => $keyFileId
        ]
    )
    ->build();

$serializer = new CompactSerializer();
$token = $serializer->serialize($jws, 0);

$data = [
    'client_id' => $clientId,
    'client_secret' => $token,
    'code' => $code,
    'grant_type' => 'authorization_code'
];

$ch = curl_init();
curl_setopt_array(
    $ch,
    [
        CURLOPT_URL => 'https://appleid.apple.com/auth/token',
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true
    ]
);
$response = curl_exec($ch);
curl_close($ch);
var_dump($token);
var_export(json_decode($response, true));