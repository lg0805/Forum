<?php  
Class BBCode{

	private static function _format_bbcode($string){

		if (preg_match('|\[([a-z]+)=?(.*?)\](.*?)\[/\1\]|', $string, $part, PREG_OFFSET_CAPTURE)) {

			$part[2][0]	= str_replace('"', "", $part[2][0]);
			$part[2][0] = str_replace("'", "", $part[2][0]);
			// $part[3][0] = _format_bbcode($part[3][0]);
			// print_r($part);exit;
			
			switch ($part[1][0]) {
			 	case 'b':
			 	case 'i':
			 	case 'u':
			 		$replace = sprintf('<%s> %s </%s>', $part[1][0], $part[3][0], $part[1][0]);
			 	break;

			 	case 'code':
			 		$replace = '<pre>'. $part[3][0] .'</pre>';
			 	break;

			 	case 'color':
			 		$replace = sprintf('<span style="color:%s">%s</span>', $part[2][0], $part[3][0]);
			 	break;

			 	case 'email':
			 		$replace = sprintf('<a href="mailto:%s">%s</a>', $part[3][0], $part[3][0]);

			 	break;
			 	
			 	default:
			 		$replace = $part[3][0];
			 		break;
			} 

			$string = substr_replace($string, $replace, $part[0][1]);

			return $string;

		} 
	}


	public static function format($string){

		$string = BBCode::_format_bbcode($string);

		$string = str_replace("\n\n", '</p><p>', $string);
		$string = str_replace("\n", '<br/>', $string);

		$string = "<p>". $string ."</p>";
		return $string;
	}
}



// $str = "[code]www.dgwx.com[/code]";
// $str = "[color='red']www.dgwx.com[/color]";
$str = "[b]www.dgwx.com[/b]";

$message = strip_tags($str);
$message = BBCode::format($str);

echo $message;
?>