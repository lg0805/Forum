<?php  
include "../lib/common.php";
include "../lib/db.php";
include "../lib/functions.php";
include "../lib/User.php";

include "401.php";

$user = User::getById($_SESSION['userId']);
if (!$user->userId) {
	die("<p>Sorry, you must be logged in to post.</p>");
}

$forum_id = isset($_GET['fid']) ? (int)$_GET['fid'] : 1;
$query = sprintf("SELECT FORUM_ID FROM %sFORUM WHERE FORUM_ID = %d", DB_TBL_PREFIX, $forum_id);

$result = mysql_query($query, $GLOBALS['DB']);
if (!mysql_num_rows($result)) {
	mysql_free_result($result);
	mysql_close($GLOBALS['DB']);
	die("<p>Invalid forum id.</p>");
}
mysql_free_result($result);

$msg_id = (isset($_GET['mid'])) ? (int)$_GET['mid'] : 1;
$query = sprintf('SELECT MESSAGE_ID FROM %sFORUM_MESSAGE WHERE MESSAGE_ID = %d', DB_TBL_PREFIX, $msg_id);
$result = mysql_query($query, $GLOBALS['DB']);
if ($msg_id && !mysql_num_rows($result)) {
	mysql_free_result($result);
	mysql_close($GLOBALS['DB']);
	die("<p>Invalid forum id.</p>");
}
mysql_free_result($result);

$msg_subject = (isset($_POST['msg_subject'])) ? trim($_POST['msg_subject']) : "";
$msg_text = (isset($_POST['msg_text'])) ? trim($_POST['msg_text']) : "";

if (isset($_POST['submitted']) && $msg_subject && $msg_text) {
	$query = sprintf("INSERT INTO %sFORUM_MESSAGE (SUBJECT, MESSAGE_TEXT, PARENT_MESSAGE_ID, FORUM_ID, USER_ID) VALUES('%s', '%s', %d, %d, %d)", DB_TBL_PREFIX, $msg_subject, $msg_text, $msg_id, $forum_id, $user->userId);
	mysql_query($query, $GLOBALS['DB']);
	header("Location:view.php?fid=".$forum_id. (($msg_id) ? "&mid=".$msg_id : ""));
} else if(isset($_POST['submitted'])){
	$message = "<p>Not all information was provided. Please corresct and resubmit. </p>";
}

ob_start();
if (isset($message)) {
	echo $message;
}

?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) .'?fid='.$forum_id. '&mid=' .$msg_id;?>" method="post">
	<div>
		<label for="msg_subject">Subject:</label>
		<input type="text" name="msg_subject" id="msg_subject" value="<?php echo htmlspecialchars($msg_subject)?>"><br>
		<label for="msg_text">Post:</label>
		<textarea name="msg_text" id="msg_text">
			<?php echo htmlspecialchars($msg_text); ?>
		</textarea><br>
		<input type="hidden" name="submitted" value="true">
		<input type="submit" value="Create">
	</div>
</form>
<?php 
$GLOBALS['TEMPLATE']['content'] = ob_get_clean();

include "../templates/template-page.php";

 ?>