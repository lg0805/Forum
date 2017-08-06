<?php 
// 利用mysql_data_seek()实现分页

include "../lib/common.php";
include "../lib/db.php";
include "../lib/user.php";
include "401.php";

error_reporting(E_ALL | E_STRICT);

$display = 2; // paginate showing 25 entries per page

$forum_id =1;

$query = sprintf('SELECT MESSAGE_ID, SUBJECT, UNIX_TIMESTAMP(MESSAGE_DATE) AS MESSAGE_DATE FROM %sFORUM_MESSAGE WHERE PARENT_MESSAGE_ID = 0 AND FORUM_ID = %d ORDER BY MESSAGE_DATE DESC', DB_TBL_PREFIX, $forum_id);

$result = mysql_query($query);


if ($total = mysql_num_rows($result)) {
	$start = (isset($_GET['start']) && ctype_digit($_GET['start']) && $_GET['start'] <= $total ) ? $_GET['start'] : 0;

	mysql_data_seek($result,$start);

	echo '<ul>';
	$count = 0;
	
	
	while(($row = mysql_fetch_assoc($result))&&($count++ <$display)) {
		echo "<li><a href='".$_SERVER['PHP_SELF']."?fid=".$forum_id."&mid=". $row['MESSAGE_ID'] . "'>";
		echo date('m/d/Y', $row['MESSAGE_DATE']) .": ";
		echo htmlspecialchars($row['SUBJECT'])."</a></li>";
	}
	echo '</ul>';
	echo "<p>";
	if ($start > 0) {
		echo '<a href="'.$_SERVER['PHP_SELF'].'?fid='. $forum_id .'&start=0">First</a> ';
		echo '<a href="'.$_SERVER['PHP_SELF'].'?fid='. $forum_id .'&start='.($start - $display) .'">&lt;Prev</a> ';
	}
	if ($total > ($start + $display)) {
		echo '<a href="'.$_SERVER['PHP_SELF'].'?fid='. $forum_id .'&start='. ($start + $display) .'">Next&gt;</a> ';
		echo '<a href="'.$_SERVER['PHP_SELF'].'?fid='. $forum_id .'&start='. ($total - $display) .'">Last</a>';
	}
	echo "</p>";
} else {
	echo '<p>This forum contains no messages.</p>';
}
mysql_free_result($result);
 ?>
