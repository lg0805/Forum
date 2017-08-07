<?php
$im = imagecreatetruecolor(120, 20);

$text_color = imagecolorallocate($im, 233, 14, 91);

imagestring($im, 5, 5, 2, "A Simple Text String", $text_color);

header("Content-Type: image/jpeg");

imagejpeg($im, "c:\\1.jpg", 75);

imagedestroy($im);
 ?>