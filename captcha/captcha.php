<?php

$letters = 'AaBbDdEeFfGgKkIiJjKkLlMmNnPpQqRrSsTtUuVvWwXxYyZz';
$font = realpath('../fonts/DroidSans.ttf');
$width = 150; 
$height = 60;
$fontsize = 22;
$backgrounds = array("../img/bg.png");
$randomBackground = $backgrounds[rand(0, sizeof($backgrounds)-1)];
$numOfLines = rand(3, 7);

$image = imagecreate($width, $height);
$image = imagecreatefrompng($randomBackground);

for ($i = 0; $i < $numOfLines; $i++){
	$color = imagecolorallocate($image, rand(0, 255), rand(0, 200), rand(0, 255));
	imageline($image, rand(0, 10), rand(1, 60), rand(160, 200), rand(1, 60), $color);
}

function generateString($letters, $length) {
    $lettersLength = strlen($letters);
    $random_string = '';

    for($i = 0; $i < $length; $i++) {
        $random_character = $letters[mt_rand(0, $lettersLength - 1)];
        $random_string .= $random_character;
    }

    return $random_string;
}

$captcha = generateString($letters, 5);

$x = $width - 120;
$x = rand($x - 10, $x + 10);
$y = $height - (($height - $fontsize) / 2);
$grayColor = imagecolorallocate($image, 106, 67, 138);
$angle = rand(-5, 5);

imagettftext($image, $fontsize, $angle, $x, $y , $grayColor, $font, $captcha);

for ($i = 0; $i < $numOfLines; $i++) {
	$color = imagecolorallocate($image, rand(0, 255), rand(0, 200), rand(0, 255));
	imageline($image, rand(0, 10), rand(1, 60), rand(160, 200), rand(1, 60), $color);
}

session_start();
$_SESSION['captcha'] = $captcha;
imagepng($image);
imagedestroy($image);

?>