<?php

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha512;

require_once __DIR__ . '/vendor/autoload.php';


$private = file_get_contents('rsa_private.pem');
$public  = '';

$config = Configuration::forAsymmetricSigner(
    new Sha512(),
    InMemory::plainText($private),
    InMemory::plainText($public)
);

$now   = new DateTimeImmutable();
$token = $config->builder()
    ->withClaim('name', 'Terrence')
    ->withClaim('helper', 'Erick')
    ->withClaim('tl', 'Jenny')
    ->getToken($config->signer(), $config->signingKey());

$jwtToken = $token->toString();

echo "----\n";
echo "$jwtToken\n";
echo "----\n";

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=> "Authorization: Bearer $jwtToken\r\n"
  )
);

$context = stream_context_create($opts);

echo file_get_contents('http://localhost:8000/server.php', false, $context);
