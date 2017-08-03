<?php 
// return a string of random text of desired length
function random_text($count, $rm_similar=true){

	// create list of characters
	$chars = array_flip(array_merge(range(0,9), range('A','Z'), range('a','z')));

	// remove similar looking characters that might cause confusion
	if ($rm_similar) {
 		unset($chars['0'], $chars['1'], $chars['2'], $chars['5'], $chars['8'], $chars['B'], $chars['I'], $chars['O'], $chars['Q'], $chars['S'], $chars['U'], $chars['V'], $chars['Z'], $chars['l'], $chars['o']);
 	}

 	// generate the string of random text 
 	for ($i=0, $text='' ; $i<$count; $i++) { 
 		$text .= array_rand($chars);
 	}

 	return $text;
}


?>