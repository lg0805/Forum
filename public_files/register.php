<?php  
// include shared code
include('../lib/common.php');
include('../lib/db.php');
include('../lib/functions.php');
include('../lib/user.php');

// start or continue session so the CAPTCHA text stored in $_SESSION is accessible
session_start();
header('Cache-control: private');
error_reporting("all");
// pripare the registration form's HTML
ob_start();
?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	<table>
		<tr>
			<td> <label for="username">Username</label> </td>
			<td> <input type="text" name="username" id="username" value="<?php if (isset($_POST['username'])) echo htmlspecialchars($_POST['username']); ?> "/></td>
		</tr>
		<tr>
			<td>
				<label for="password1">Password</label>
			</td>
			<td>
				<input type="password" name="password1" value="" id="password1" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="password2">Password Again</label>
			</td>
			<td>
				<input type="password" name="password2" id="password2" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="email">Email Address</label>
			</td>
			<td>
				<input type="text" name="email" id="email" value="<?php
				 if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>
				 ">
			</td>
		</tr>
		<tr>
			<td>
				<label for="captcha">Verify</label>
			</td>
			<td>
				<!-- Enter text seen in this image<br> -->
				<img src="img/aptcha.php?nocache=<?php echo time(); ?>" style="vertical-align:text-top" />
				<input type="text" name="captcha" id="captcha" style="width:84px; padding-left:8px;" />
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" value="Sign Up" name="submit">
			</td>
			<td>
				<input type="hidden" name="submitted" value="1">
			</td>
		</tr>

	</table>
</form>

<?php  
$form = ob_get_clean();

if (!isset($_POST['submitted'])) {
// if ($_SERVER['REQUEST_METHOD']=="POST") 
// {
	$GLOBALS['TEMPLATE']['content'] = $form;
} 
else 
{
	// echo "aaa";
	$password1 = isset($_POST['password1']) ? $_POST['password1'] : "";
	$password2 = isset($_POST['password2']) ? $_POST['password2'] : "";
	$password = ($password1 && $password1 == $password2) ? sha1($password1) : "";

	$captcha = (isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION['captcha']);
	// echo $captcha;
	//echo User::validateUsername($_POST['username']);
	// echo $password;
	if (User::validateUsername($_POST['username']) && $password && User::validateEmailAddr($_POST['email']) && $captcha) 
	{
		echo "222";
		$user = User::getByUsername($_POST['username']);
		if ($user->userId) 
		{
			$GLOBALS['TEMPLATE']['content'] = "<p><strong>Sorry, that account already exsits.</strong></p><p>Please try a different username.</p>";
			$GLOBALS['TEMPLATE']['content'] .= $form;
		}
		else
		{
			// echo "test";
			// exit;
			$user = new User();
			$user->username = $_POST['username'];
			$user->password = $password;
			$user->emailAddr = $_POST['email'];
			$token = $user->setInactive();

			$GLOBALS['TEMPLATE']['content'] = '<p><strong>Thank you for registering.</strong></p><p> Be sure to verify your account by visiting <a href="verify.php?uid=' .$user->userId.'&token='.$token.'">verify.php</a></p>';
		}
		
	} else {
		$GLOBALS['TEMPLATE']['content'] .= "<p>You provided some invalid data. Please fill in fields correctly so we can register your user acount.</p>";
		$GLOBALS['TEMPLATE']['content'].=$form;
	}
}
include '../templates/template-page.php';
?>