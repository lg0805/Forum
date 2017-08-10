<?php 

	$pattern = '/<a.*?(?:|\\t|\\r|\\n)?href=[\'"]?(.+?)[\'"]?(?:(?:|\\t|\\n)+.*?)?>(.+?)<\/a.*?>/sim';
	$subject = "<a href='www.baidu.com'>百度</a>";

	if (preg_match($pattern, $subject, $result)) {
		echo "匹配成功";
	} else {
		echo "没有找到有效的标签";
	}

	print_r($result);

	$pattern2 = '/^(<a).*?/';
	$subject2 = '<a href="www.baidu.com">';

	if (preg_match($pattern2, $subject2, $result2)) {
		echo "匹配成功";
	} else {
		echo "没有找到有效的标签";
	}

	print_r($result2);

?>