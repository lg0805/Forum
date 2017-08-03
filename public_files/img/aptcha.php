<?php 
include('../../lib/functions.php');

if (!isset($_SESSION)) {
	session_start();
	header('Cache-control: private');
}

// create a 60*20 pixel image
$width = 65;
$height = 20;
$image = imagecreate($width, $height);

// fill the image backbround color
$bg_color = imagecolorallocate($image, 0x33, 0x66, 0xff);
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// fetch random text
$text = random_text(5);

// determine x and y coordinates for centering text
// 确定用于居中文本的x和y坐标

$font = 5;
$x = imagesx($image) / 2 - strlen($text) * imagefontwidth($font) /2;
// imagesx — 取得图像宽度
$y = imagesy($image) /2 - imagefontheight($font) /2;
// imagefontheight — 取得字体高度

// write text on image
$fg_color = imagecolorallocate($image, 0xff, 0xff, 0xff);
imagestring($image, $font, $x, $y, $text, $fg_color);
// imagestring — 水平地画一行字符串

// save the CAPTCHA string for later comparison
// 保存验证码字符串用于后面的比较
$_SESSION['captcha'] = $text;

// output the image
header("content-type: image/png");
imagepng($image);

imagedestroy($image);
?>