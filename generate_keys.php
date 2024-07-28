<?php

require 'vendor/autoload.php';

use phpseclib3\Crypt\RSA;

// Generate private key
$privateKey = RSA::createKey();

// Save private key to a file
file_put_contents('storage/app/private.key', $privateKey);

// Generate public key from private key
$publicKey = $privateKey->getPublicKey();

// Save public key to a file
file_put_contents('storage/app/public.key', $publicKey);

echo "Keys generated successfully.\n";
