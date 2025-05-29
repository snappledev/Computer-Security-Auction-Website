<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
function get_key() {
    $config = include($_SERVER['DOCUMENT_ROOT'] . '/../secure/key.php');
    return $config['key'] ?? null;
}

function encrypt_data($plaintext) {
    $key = get_key();

    $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $ciphertext);
}

function decrypt_data($ciphertext) {
    $key = get_key();
    $data = base64_decode($ciphertext);
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($data, 0, $iv_length);
    $encrypted_data = substr($data, $iv_length);
    return @openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}
function decrypt_image($imagePath) {
    $encryptedData = file_get_contents($imagePath);
    $decryptedData = decrypt_data($encryptedData);
    return $decryptedData;
}
function encrypt_image($imagePath) {
    $encryptedData = file_get_contents($imagePath);
    $encryptedData = encrypt_data($encryptedData);
    return $encryptedData;
}
?>