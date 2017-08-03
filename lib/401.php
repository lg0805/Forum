
<script type="text/javascript">
	window.seconds = 10;
	window.onload = function(){
		if (window.seconds!=0) {
			document.getElementById('secondDisplay').innerHTML = ' ' + window.seconds + ' second' + ((window.seconds>1)?'s.':'.');
			window.seconds--;
			setTimeout(window.onload, 1000);
		} else {
			window.location = 'http://www.baidu.com';
		};
	}
</script>

<p>You will be redirected to the login page in <span id = "secondDisplay">10 seconds.</span></p>