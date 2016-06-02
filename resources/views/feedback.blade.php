<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="5">
<title>Feedback</title>

<style>
body {
	font-family: arial, sans-serif;
}
</style>
</head>
<body>
	<center>
		<h1>Feedback-o-meter (eduroam only)</h1>
		<img src="/rating/{{ $contactmoment->id }}" width="500" height="100"
			style="margin: 25px 0;" /></br> <img
			src="/qr?data=<?php print htmlentities(rawurlencode($url));?>"
			width="400" height="400" />
		<p><?php print htmlentities($url); ?></p>
	</center>
</body>
</html>