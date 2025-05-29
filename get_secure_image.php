<?php

include('encryption_manager.php');  // Include your encryption functions
session_start();
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://kit.fontawesome.com https://code.jquery.com https://buttons.github.io https://api.nepcha.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.nepcha.com; frame-ancestors 'self';object-src 'none'; base-uri 'self';");
#if (!isset($_SESSION['username'])) {
#    header("HTTP/1.0 403 Forbidden");
#    echo "You are not authorized to view this image.";
#    exit();
#}

if (isset($_GET['image'])) {
    $imagePath = 'auction_images/' . $_GET['image'];  // Get the image file path

    if (file_exists($imagePath)) {
        $encryptedData = file_get_contents($imagePath);
        $decryptedData = decrypt_data($encryptedData);
        $fileExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
        if ($fileExtension == 'jpg' || $fileExtension == 'jpeg') {
            header('Content-Type: image/jpeg');
        } elseif ($fileExtension == 'png') {
            header('Content-Type: image/png');
        } elseif ($fileExtension == 'gif') {
            header('Content-Type: image/gif');
        } else {
            header('Content-Type: application/octet-stream');
        }

        // Output the decrypted image data directly to the browser
        echo $decryptedData;
    } else {
        header("HTTP/1.0 404 Not Found");
    }
} else {
    header("HTTP/1.0 400 Bad Request");
} ?>