<?php
$serviceAccount = json_decode(file_get_contents('service-account.json'), true);

$header = [
    "alg" => "RS256",
    "typ" => "JWT"
];

$now = time();

$payload = [
    "iss" => $serviceAccount["client_email"],
    "scope" => "https://www.googleapis.com/auth/firebase.messaging",
    "aud" => "https://oauth2.googleapis.com/token",
    "iat" => $now,
    "exp" => $now + 3600
];

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

$jwtHeader = base64url_encode(json_encode($header));
$jwtPayload = base64url_encode(json_encode($payload));

$signatureInput = "$jwtHeader.$jwtPayload";

openssl_sign($signatureInput, $signature, $serviceAccount['private_key'], "SHA256");

$jwtSignature = base64url_encode($signature);

$jwt = "$jwtHeader.$jwtPayload.$jwtSignature";

echo $jwt;
