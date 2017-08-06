<?php 
// includes shared code 
include "../lib/common.php";
include "../lib/db.php";
include "../lib/functions.php";
include "../lib/user.php";
header("Content-Type:text/html; charset=utf-8");
error_reporting(0);

// start or continue the session
session_start();
header("Cache-control: private");

if (isset($_GET['login'])) {

	if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])) {
		$user = (User::validateUsername($_POST['username'])) ? User::getByUsername($_POST['username']) : new User();

		if ($user->userId &&  $user->password == sha1($_POST['password'])) {
			$_SESSION['access'] = TRUE;
			$_SESSION['userId'] = $user->userId;
			$_SESSION['username'] = $user->username;
			// var_dump($_SESSION);
			// exit();
			header("Location: main.php");
			
		} else {
			$_SESSION['access'] = FALSE;
			$_SESSION['username'] = FALSE;
			header('Location: 401.php');
		}

	 // var_dump($user);
	} else {
		// missing credentials
		
		$_SESSION['access'] = FALSE;
		$_SESSION['username'] =null;
		header("Location: 401.php");
	}
	exit();
} else if (isset($_GET['logout'])) {
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time() - 42000, "/");
	}

	$_SESSION = array();
	session_unset();
	session_destroy();
}

ob_start();
?>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>?login=login" method="post">
	<table>
		<tr>
			<td><label for="username">Username</label> </td>
			<td><input type="text" name="username" id="username"></td>
		</tr>
		<tr>
			<td><label for="password">Password</label></td>
			<td><input type="password" name="password" id="password"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Log In"></td>
		</tr>
	</table>

</form>

<?php 
$GLOBALS['TEMPLATE']['content'] = ob_get_clean();
include '../templates/template-page.php';


 ?>