<?php 
require_once('../lib/functions.php');
// date_default_timezone_set("PRC");
// // $expires = mktime(0,0,0,7, 1, 2017);
// // echo $expires."<br>";
// // $lastMonth = strtotime('last month', $expires);
// // echo $lastMonth;
// // echo date("Y-m-d", $lastMonth);

// var_dump(checkdate(7, 31, 2017));
// // 	echo "非法日期！";
// // 	# code...
// // }

/*
验证表单输入：信用卡
 */
// function is_valid_credit_card($s){

// 	$s = strrev(preg_replace('/[^\d]/', '', $s));
// 	//删除非数字字符，并反转
// 	$sum = 0;
// 	for($i =0, $j = strlen($s); $i < $j; $i++ ) {
// 		if (($i % 2) == 0 ){
// 			$val = $s[$i];
// 		} else {
// 			$val = $s[$i] * 2;
// 			if ($val > 9) {
// 				$val -= 9;
// 			}
// 		}

// 		$sum += $val;
// 		# code...
// 	}
// 	return (($sum % 10) ==0);
// }

// if(!is_valid_credit_card("4111111111111111")){
// 	echo 'Sorry, that card number is invalid!';
// };

$test = 3;

echo <<<tab
<form method="post" action="test.php">
<input type="text" name="username">
</form>
tab;

echo isset($_POST['username'])?"ok":"fail";

setInactive();

?>