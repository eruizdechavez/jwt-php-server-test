<?php

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

require_once __DIR__ . '/vendor/autoload.php';

$private = '';
$public  = file_get_contents('rsa_public.pem');
$token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION'] ?? 'none');

$config = Configuration::forAsymmetricSigner(
    new Sha512(),
    InMemory::plainText($private),
    InMemory::plainText($public)
);

$signedWithConstraint = new SignedWith(new Sha512(), InMemory::plainText($public));
$config->setValidationConstraints($signedWithConstraint);

try {
    /**
     @var UnencryptedToken
     */
    $jwtToken = $config->parser()->parse($token);

    $config->validator()->assert($jwtToken, $signedWithConstraint);
    
    $claims = $jwtToken->claims();

    echo "Name: {$claims->get('name')}, Helper: {$claims->get('helper')}, TL: {$claims->get('tl')}\n";
} catch (RequiredConstraintsViolated $e) {
    // var_dump($e->violations());
    echo "not ok\n";
}
