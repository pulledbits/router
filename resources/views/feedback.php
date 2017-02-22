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
		<h1>Feedback-o-meter</h1>
        <?php $url = $this->url('/feedback/' . $contactmoment->id . '/supply'); ?>
		<img src="<?=$this->url('/rating/' . rawurlencode($contactmoment->id)) ?>" width="500" height="100"
			style="margin: 25px 0;" /></br> <img
			src="<?=$this->url('/qr?data=' . rawurlencode($url)) ?>"
			width="400" height="400" />
		<p><?=$url?></p>
	</center>
</body>
</html>