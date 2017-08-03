<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>
	<?php 
		if (!empty($GLOBALS['TEMPLATE']['title'])) {
			echo $GLOBALS['TEMPLATE']['title'];
		}
	?>
	</title>
	<link rel="stylesheet" href="css/styles.css" />
	<?php  
	if (!empty($GLOBALS['TEMPLATE']['extra_head'])) {
		echo $$GLOBALS['TEMPLATE']['extra_head'];
	}
	?>
</head>
<body>
	<div id="header">
		<?php  
			if (!empty($GLOBALS['TEMPLATE']['title'])) {
				echo $GLOBALS['TEMPLATE']['title'];
			}
		?>
	</div>
	<div id="content">
		<?php  
			if (!empty($GLOBALS['TEMPLATE']['content'])) {
				echo $GLOBALS['TEMPLATE']['content'];
			}
		?>
	</div>
	<div id="footer">
		Copyright &copy; <?php date_default_timezone_set("PRC"); echo date('Y'); ?>
	</div>
</body>
</html>