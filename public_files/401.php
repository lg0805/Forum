<?php
include "../lib/common.php";

!isset($_SESSION) ? session_start():"";

header("Cache-control: private");

if (!isset($_SESSION['access']) || $_SESSION['access'] != TRUE) {
	header("HTTP/1.0 401 Authorriaztion Error");
	ob_start();
	// echo '<meta http-equiv="refresh" content="10; url=login.php"/>';

?>
<script type="text/javascript">
	window.seconds = 10;
	window.onload = function(){
		if (window.seconds!=0) {
			document.getElementById('secondDisplay').innerHTML = ' ' + window.seconds + ' second' + ((window.seconds>1)?'s.':'.');
			window.seconds--;
			setTimeout(window.onload, 1000);
		} else {
			window.location = './login.php';
		};
	}


</script>

<p>You will be redirected to the login page in <span id = "secondDisplay">10 seconds.</span></p>

<?php  
}
?>