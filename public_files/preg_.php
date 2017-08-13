<?php 
	error_reporting(E_ALL);
	/*$pattern = '/<a.*?(?:|\\t|\\r|\\n)?href=[\'"]?(.+?)[\'"]?(?:(?:|\\t|\\n)+.*?)?>(.+?)<\/a.*?>/sim';
	$subject = "<a href='www.baidu.com'>百度</a>";

	if (preg_match($pattern, $subject, $result)) {
		echo "匹配成功";
	} else {
		echo "没有找到有效的标签";
	}

	print_r($result);*/

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
	
	/*
	 * --参考资料--
	 * preg_match_all使用心得分享_php技巧_脚本之家
     * http://www.jb51.net/article/46435.htm
     */
    
    // 使用反向引用时，如果使用单引可表示为'\1',如果使用使用双引号，则表示为"\\1"
	$pattern2 = '#\[([a-z]+)=?(.*?)\](.*?)\[/\1\]#';	 
	// $pattern2 = "#\[([a-z]+)=?(.*?)\](.*?)\[/[a-z]+\]#";	
	// $pattern2 = "|(.*?)|";	// 结果：yes
	
	$subject2 = "[color=green]test[/color]";

	if (preg_match($pattern2, $subject2, $part, PREG_OFFSET_CAPTURE)) {
		echo "匹配成功";
	} else {
		echo "没有找到有效的标签";
	}

	var_dump($part);

?>