<?php  
error_reporting(E_ALL | E_STRICT);
include "../lib/common.php";
include "../lib/user.php";
// include "../lib/functions.php";
// include "../lib/functions.php";
include "../lib/db.php";
include "401.php"; 

$user = User::getById($_SESSION['userId']);

// var_dump($user);
// echo ~intval($user->permission);
// echo User::CREATE_FORUM;

// ~ 求反运算， & 按位与运算
if (~intval($user->permission) & User::CREATE_FORUM) {
	die("<p>Sorry, you do not have sufficient privileges to create new forums.</p>");
}

$forum_name = (isset($_POST['forum_name'])) ? trim($_POST['forum_name']) : '';
$forum_desc = (isset($_POST['forum_desc'])) ? trim($_POST['forum_desc']) : '';

if (isset($_POST['submitted']) && $forum_name && $forum_desc) {
	$query = sprintf("INSERT INTO %sFORUM(forum_name, description) VALUES('%s', '%s')", DB_TBL_PREFIX, $forum_name, $forum_desc);
	// echo $query;exit;
	mysql_query($query, $GLOBALS['DB']);
	// echo mysql_affected_rows();
	header("Location: view.php");
} else if(isset($_POST['submitted'])){
	$message = "<p>Not all information was provided. please correct and resubmit.</p>";
}

ob_start();
if (isset($message)) {
	echo $message;
}


?>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
	<div>
		<label for="forum_name">Forum Name:</label>
		<input type="text" name="forum_name" id="forum_name" value="<?php echo htmlspecialchars($forum_name); ?>"><br>
		<label for="forum_desc">Description:</label>
		<input type="text" name="forum_desc" id="forum_desc" value="<?php echo htmlspecialchars($forum_desc); ?>">
		<br>
		<input type="hidden" name="submitted" value="true">
		<input type="submit" value="Create">
	</div>
</form>

<?php  
$GLOBALS['TEMPLATE']['content'] = ob_get_clean();

include "../templates/template-page.php";
?>