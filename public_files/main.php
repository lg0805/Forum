<?php 
include "../lib/common.php";
include "../lib/db.php";
include "../lib/functions.php";
include "../lib/User.php";

error_reporting(0);

//include "401.php";
!isset($_SESSION)?session_start():'';
// print_r($_SESSION);
// exit;
$user = User::getById($_SESSION['userId']);
// echo isset($_POST['email'])?"ok":"fail";

ob_start();
 ?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
	<table>
		<tr>
			<td>
				<label for="username">Username</label> 
			</td>
			<td>
				<input type="text" name="username" id="username" disabled="disabled" readonly="readonly" value="<?php echo $user->username; ?>">
			</td>
		</tr>
		<tr>
			<td>
				<label for="email">Email Address</label>
			</td>
			<td>
				<input type="text" name="email" value="<?php echo (isset($_POST['email']))? htmlspecialchars($_POST['email']):$user->emailAddr; ?>" id="email">
			</td>
		</tr>
		<tr>
			<td>
				<label for="password1">New Password</label>
			</td>
			<td>
				<input type="password" name="password1" id="password1" value="">
			</td>
		</tr>
		<tr>
			<td>
				<label for="password2">Password Again</label>
			</td>
			<td>
				<input type="password" name="password2" id="password2" value="">
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" value="Save" />
			</td>
			<td>
				<input type="hidden" name="submitted" value="1">
			</td>
		</tr>
	</table>
</form>

<?php 
$form = ob_get_clean();


// show the form if this the first time the page is viewed
if (!isset($_POST['submitted'])) {
	// 没有点击提交表单执行此代码
	$GLOBALS['TEMPLATE']['content'] = $form;
} else {
	// 点击提交，执行代码
	$password1 = (isset($_POST['password1']) && !empty($_POST['password1'])?sha1($_POST['password1']):$user->password);

	$password2 = (isset($_POST['password2']) && !empty($_POST['password2'])?sha1($_POST['password2']):$user->password);
	$password = ($password1 == $password2)?$password1: "";

	// update the record if the input validate
	if (User::validateEmailAddr($_POST['email']) && $password) {
		$user->emailAddr = $_POST['email'];
		$user->password = $password;
		$user->save();

		$GLOBALS['TEMPLATE']['content'] = '<p><strong>Information in your record has been updated.</strong></p>';
		include "401.php";
	} else {
		$GLOBALS['TEMPLATE']['content'] .= '<p><strong>You provided some invalid data.</strong></p>';
		$GLOBALS['TEMPLATE']['content'] .=$form;
		include "401.php";
	}

}

include "../templates/template-page.php";
?>