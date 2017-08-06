<?php 	
include "../lib/common.php";
include "../lib/db.php";
include "../lib/functions.php";
include "../lib/user.php";

session_start();
header("content-type:text/html; charset=utf8;");

$forum_id = (isset($_GET['fid'])) ? (int)$_GET['fid'] : 0;
$msg_id = isset($_GET['mid']) ? (int)$_GET['mid'] : 0;

ob_start();
// var_dump($_SESSION);
if ($forum_id) {
	$query = sprintf("SELECT FORUM_NAME FROM %sFORUM WHERE FORUM_ID = %d", DB_TBL_PREFIX, $forum_id);
	$result = mysql_query($query, $GLOBALS['DB']);

	if(!mysql_num_rows($result)){
		die("<p>Invalid forum id.</p>");
	}

	$row = mysql_fetch_assoc($result);
	echo "<h1>" . htmlspecialchars($row['FORUM_NAME']) . "</h1>";
	mysql_free_result($result);

	if ($msg_id) {
		$query = sprintf("SELECT MESSAGE_ID FROM %sFORUM_MESSAGE WHERE MESSAGE_ID = %d", DB_TBL_PREFIX, $msg_id);
		$result = mysql_query($query, $GLOBALS['DB']);

		if(!mysql_num_rows($result)){
			mysql_free_result($result);
			die('<p>Invalid forum_id.</p>');
		}

		mysql_free_result($result);

		echo "<p><a href='view.php?fid=".$forum_id."'>Back to forum threads.</a></p>";
	} else {
		echo '<p><a href="view.php">Back to forum list.</a></p>';

		if (isset($_SESSION['access'])) {
			echo '<p><a href="add_post.php?fid='.$forum_id.'">Post new message</a></p>';
		}
	}
} else {
	echo '<h1>Forums</h1>';
	if (isset($_SESSION['userId'])) {

		$user = User::getById($_SESSION['userId']);
		
		if ((int)$user->permission & User::CREATE_FORUM) {
			echo "<p><a href='add_forum.php' >Creat new forum</a></p>";
		}
	}
}

if ($forum_id && $msg_id) {
	$query = sprintf('
		SELECT
			USERNAME, FORUM_ID, MESSAGE_ID, PARENT_MESSAGE_ID, SUBJECT, MESSAGE_TEXT, UNIX_TIMESTAMP(MESSAGE_DATE) AS MESSAGE_DATE
		FROM %sFORUM_MESSAGE M JOIN %sUSER U ON M.USER_ID = U.USER_ID 
		WHERE
			MESSAGE_ID = %d OR PARENT_MESSAGE_ID = %d 
		ORDER BY 
			MESSAGE_DATE ASC', DB_TBL_PREFIX, DB_TBL_PREFIX, $msg_id, $msg_id);
	
	$result = mysql_query($query, $GLOBALS['DB']);

	echo '<table border=1>';
	while($row = mysql_fetch_assoc($result)){
		echo "<tr>";
		echo '<td style="text-align:center; vertical-align:top; width:150px;">';
		
		if (file_exists('avatars/'.$row['USERNAME'].'.jpg')) {
			echo '<img src="avatars/'.$row['USERNAME'].'.jpg"';
			
		}else{
			echo '<img src="img/default_avatar-s.jpg" />';
		}

		echo '<br/><strong>'.$row['USERNAME'].'</strong><br/>';
		echo date('m/d/Y<\b\r/>H:i:s', $row['MESSAGE_DATE']).'</td>';

		echo '<td style="vertical-align:middle">';
		echo '<div><strong>'.htmlspecialchars($row['SUBJECT']).'</strong></div>';
		echo '<div>'.htmlspecialchars($row['MESSAGE_TEXT']).'</div>';
		echo '<div style="text-align:right">';
		// echo "test";
		echo '<a href="add_post.php?fid='.$row['FORUM_ID'].'&mid='.(($row['PARENT_MESSAGE_ID'] != 0) ? $row['PARENT_MESSAGE_ID'] : $row['MESSAGE_ID']).'">Reply</a></div></td>';
		echo '</tr>';
		
	}
	echo "</table>";
	mysql_free_result($result);
} else if ($forum_id) { // generate thread view
	$query = sprintf('SELECT MESSAGE_ID, SUBJECT, UNIX_TIMESTAMP(MESSAGE_DATE) AS MESSAGE_DATE FROM %sFORUM_MESSAGE WHERE PARENT_MESSAGE_ID = 0 AND FORUM_ID = %d ORDER BY MESSAGE_DATE DESC', DB_TBL_PREFIX, $forum_id);
	$result = mysql_query($query, $GLOBALS['DB']);

	if (mysql_num_rows($result)) {
		echo "<ul>";
		while ($row = mysql_fetch_assoc($result)) {
			echo '<li><a href="view.php?fid='.$forum_id.'&mid='.$row['MESSAGE_ID'].'">';
			echo date('m/d/Y', $row['MESSAGE_DATE']) .': ';
			echo htmlspecialchars($row['SUBJECT']) .'</a></li>';
		}
		echo '</ul>';
	} else {
		echo '<p>This forum contains no messages.</p>';
	}
	mysql_free_result($result);
} else {
	// generate forums view
	$query = sprintf('SELECT FORUM_ID, FORUM_NAME, DESCRIPTION FROM %sFORUM ORDER BY FORUM_NAME ASC, FORUM_ID ASC', DB_TBL_PREFIX);
	$result = mysql_query($query);

	echo "<ul>";

	while ($row = mysql_fetch_assoc($result)) {
		echo "<li><a href='".htmlspecialchars($_SERVER['PHP_SELF'])."?fid=".$row['FORUM_ID']."'>";
		echo htmlspecialchars($row['FORUM_NAME']) .": ";
		echo htmlspecialchars($row['DESCRIPTION'])."</a></li>";
	}
	echo "</ul>";
	mysql_free_result($result);
}
$GLOBALS['TEMPLATE']['content'] = ob_get_clean();

include '../templates/template-page.php';
?>