<?php 
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';

ob_start();

 ?>

 <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	<p>Enter your username. A new password will be send to the email address on file.</p>
	<table>
		<tr>
			<td><label for="username">Username</label> </td>
			<td><input type="text" name="username" id="username" value="<?php if (isset($_POST['username'])) echo htmlspecialchars($_POST['username']); ?>"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Submit"></td>
			<td><input type="hidden" name="submitted" value="1"></td>
		</tr>
	</table>
 </form>

 <?php  
$form = ob_get_clean();

if (!isset($_POST['submitted'])) {
	$GLOBALS['TEMPLATE']['content'] = $form;
} else {

	// validate username
	if (User::validateUsername($_POST['username'])) {
		$user = User::getByUsername($_POST['username']);
		if (!$user->userId) {
			$GLOBALS['TEMPLATE']['content'] = "<p><strong>Sorry, that account does not exist. </strong></p><p>Please try a didfferent username. </p>";
			$GLOBALS['TEMPLATE']['content'] .= $form;
			# code...
		} else {
			$password = random_text(8);

			$message = "Your new password is: ".$password;

			$GLOBALS['TEMPLATE']['content'] = "<p><strong>A new password has been emailed to you.</strong></p>";

			$user->password = $password;
			$user->save();

			// if(mail($user->emailAddr, 'New password', $message)){
			// 	echo "mail send ok!";
			// };
			// echo $password;
		}
		# code...
	} else {
		$GLOBALS['TEMPLATE']['content'] .= "<p><strong>You did not provide a valid username</strong></p><p>Please try again.</p>";
		$GLOBALS['TEMPLATE']['content'] .=$form;
	}
}

//echo $form;
include "../templates/template-page.php";



 ?>