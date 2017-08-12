<?php 

	$pattern = '/<a.*?(?:|\\t|\\r|\\n)?href=[\'"]?(.+?)[\'"]?(?:(?:|\\t|\\n)+.*?)?>(.+?)<\/a.*?>/sim';
	$subject = "<a href='www.baidu.com'>百度</a>";

	if (preg_match($pattern, $subject, $result)) {
		echo "匹配成功";
	} else {
		echo "没有找到有效的标签";
	}

	print_r($result);
	// 匹配成功Array
	// (
 	//    [0] => <a href='www.baidu.com'>百度</a>
 	//    [1] => w
 	//    [2] => 百度
	// )

	/*
	1、*和+后加？后，表示懒惰模式，当$subject2 = "1999b99"时：
	$pattern2 = '/1.*?99/';	// 结果：199, ".*?"组合可直接忽略
	$pattern2 = '/1.+?99/';	// 结果：1999
	2、*、+后面未加?,表示贪婪模式，
	$pattern2 = '/1.*99/';	// 结果：1999b99
	$pattern2 = '/1.+99/';	// 结果：1999b99
	 */


	// $pattern2 = '/1.*99/';	// 结果：1999b99
	// $pattern2 = '/1.*?99/';	// 结果：199
	// $pattern2 = '/1.+99/';	// 结果：1999b99
	// $pattern2 = '/1.+?99/';	// 结果：1999
	// $pattern2 = "/1.?99/";	// 结果：1999
	
	$pattern2 = "/\bfor\b/";	// 结果：yes
	
	$subject2 = "for fore";

	if (preg_match($pattern2, $subject2, $result2)) {
		echo "匹配成功";
	} else {
		echo "没有找到有效的标签";
	}

	var_dump($result2);

?>