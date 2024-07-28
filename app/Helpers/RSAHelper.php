<?php
// app/Helpers/RSAHelper.php

namespace App\Helpers;

use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\RSA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class RSAHelper
{
    private $privateKey;
    private $publicKey;

    public function __construct()
    {
        // Load private key
        $privateKey = file_get_contents(storage_path('app/private.key'));
        $this->privateKey = RSA::load($privateKey);

        // Load public key
        $publicKey = file_get_contents(storage_path('app/public.key'));
        $this->publicKey = RSA::load($publicKey);
    }

    public function encryptDocument($filePath)
    {
        // Generate a random AES key
        $aesKey = random_bytes(32); // 256-bit key for AES-256
        $iv = random_bytes(16); // 128-bit IV for AES

        // Read file content
        $document = file_get_contents($filePath);

        // Encrypt document with AES
        $aes = new AES('cbc');
        $aes->setKey($aesKey);
        $aes->setIV($iv);
        $encryptedDocument = $aes->encrypt($document);

        // Encrypt AES key with RSA
        $encryptedAesKey = $this->publicKey->encrypt($aesKey);

        // Return base64 encoded result
        return base64_encode(json_encode([
            'key' => base64_encode($encryptedAesKey),
            'iv' => base64_encode($iv),
            'data' => base64_encode($encryptedDocument)
        ]));
    }

    public function decryptDocument($encryptedData)
    {
        // Decode base64 encoded data
        $decodedData = json_decode(base64_decode($encryptedData), true);
        $encryptedAesKey = base64_decode($decodedData['key']);
        $iv = base64_decode($decodedData['iv']);
        $encryptedDocument = base64_decode($decodedData['data']);

        // Decrypt AES key with RSA
        $aesKey = $this->privateKey->decrypt($encryptedAesKey);

        // Decrypt document with AES
        $aes = new AES('cbc');
        $aes->setKey($aesKey);
        $aes->setIV($iv);
        $decryptedDocument = $aes->decrypt($encryptedDocument);

        return $decryptedDocument;
    }

    public function signDocument($filePath)
    {
        // Read file content
        $document = file_get_contents($filePath);

        // Hash document content
        $hash = hash('sha256', $document);

        // Sign hash with RSA private key
        $this->privateKey->withHash('sha256');
        $signature = $this->privateKey->sign($hash);

        return base64_encode($signature);
    }

    public function verifySignature($filePath, $signature)
    {
        // Read file content
        $document = file_get_contents($filePath);

        // Hash document content
        $hash = hash('sha256', $document);

        // Decode base64 encoded signature
        $signature = base64_decode($signature);

        // Verify signature with RSA public key
        $this->publicKey->withHash('sha256');
        $verified = $this->publicKey->verify($hash, $signature);

        return $verified;
    }

    public function generateQRCode($data)
    {
        // Ensure the directory exists
        $qrCodeDirectory = storage_path('app/public/qrcodes');
        if (!is_dir($qrCodeDirectory)) {
            mkdir($qrCodeDirectory, 0755, true);
        }

        // Generate QR code
        $filename = 'qrcode_' . uniqid() . '.png';
        $path = $qrCodeDirectory . '/' . $filename;
        QrCode::format('png')->size(400)->generate($data, $path);

        return $filename;
    }
}
