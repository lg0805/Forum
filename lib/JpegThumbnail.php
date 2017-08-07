<?php  
class JpegThumbnail{

	public $width;
	public $height;

	public function __construct($width = 50, $height = 50){

		$this->width = $width;
		$this->height = $height;
	}

	public function generate($src, $dest = ''){

		header("Content-Type:image/jpeg");
		list($width, $height) = getimagesize($src);
		
		// determine if resize is necessary
		if (($lowest = min($this->width/$width, $this->height/$height)) < 1) {
			
			$tmp = imagecreatefromjpeg($src);

			// resize
			$sm_width = floor($lowest * $width);	// floor()取整
			$sm_height = floor($lowest * $height);

			$img = imagecreatetruecolor($sm_width, $sm_height);
			imagecopyresized($img, $tmp, 0, 0, 0, 0, $sm_width, $sm_height, $width, $height);
			imagedestroy($tmp);
		} else {	// image is already thumbnail size and resize not necessary
			imagecreatefromjpeg($src);
		}

		if ($dest) {
			// echo $dest;exit;
			imagejpeg($img, $dest ,100);
			imagedestroy($img);
		} else {
			// imagejpeg($tmp);
			return $img;
		}

	}
}

//header("Content-Type:image/jpeg");
$jgp = new JpegThumbnail();

$jgp->generate("1.jpg");

