<?php
session_start();

$captcha = substr(str_shuffle("123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ"), 0, 4);
$_SESSION['capcha_key'] = $captcha;
$max_width = 140;
$captchaImage = imagecreate($max_width, 40); // Image dimensions
$bgColor = imagecolorallocate($captchaImage, rand(200, 255), rand(200, 255), rand(200, 255));
$textColor = imagecolorallocate($captchaImage, rand(0, 100), rand(0, 100), rand(0, 100));
$lineColor = imagecolorallocate($captchaImage, rand(100, 200), rand(100, 200), rand(100, 200));
$dotColor = imagecolorallocate($captchaImage, rand(50, $max_width), rand(50, $max_width), rand(50, $max_width));
for ($i = 0; $i < 5; $i++) {
    $x1 = rand(0, $max_width);
    $y1 = rand(0, 50);
    $x2 = rand(0, $max_width);
    $y2 = rand(0, 50);
    $x3 = rand(0, $max_width);
    $y3 = rand(0, 50);
    imageline($captchaImage, $x1, $y1, $x2, $y2, $lineColor);
    imageline($captchaImage, $x2, $y2, $x3, $y3, $lineColor);
}
for ($i = 0; $i < 500; $i++) {
    imagesetpixel($captchaImage, rand(0, $max_width), rand(0, 50), $dotColor);
}
for ($i = 0; $i < 8; $i++) {
    imageline($captchaImage, rand(0, $max_width), rand(0, 50), rand(0, $max_width), rand(0, 50), $lineColor);
}
$fontFile = __DIR__ . "/arial.ttf";
if (!file_exists($fontFile)) {
    die("");
}

for ($i = 0; $i < strlen($captcha); $i++) {
    $fontX = 15 + ($i * 30);
    $fontY = rand(20, 35); // Adjust text vertical position
    $angle = rand(-15, 15); // Slight rotation for each character
    imagettftext($captchaImage, 20, $angle, $fontX, $fontY, $textColor, $fontFile, $captcha[$i]);
}
header("Content-type: image/png");
imagepng($captchaImage);
imagedestroy($captchaImage);
?>