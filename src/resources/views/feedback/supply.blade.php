<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Feedback</title>
<style>
body {
	font-family: arial, sans-serif;
}
</style>
</head>
<body>
	<center>
		<h1>Hoeveel sterren?</h1>
<?php
for ($i = 0; $i < 5; $i ++) {
    if ($rating === null) {
        $image = $uris['star'];
    } elseif ($i < $rating) {
        $image = $uris['star'];
    } else {
        $image = $uris['unstar'];
    }
    ?><a
			href="?rating=<?php print htmlentities(rawurldecode($i + 1));?>"><img
			src="<?php print htmlentities($image); ?>" width="100" /></a><?php
}
if ($rating !== null) {
    ?>
    <form action="supply" method="post">
    {{ csrf_field() }}
			<h1>Waarom?</h1>
			<input type="hidden" name="rating"
				value="<?php print htmlentities($rating);?>" />
			<textarea rows="5" cols="75" name="explanation"><?php print htmlentities($explanation); ?></textarea>
			<p>
				<input type="submit" value="Verzenden!" />
			</p>
		</form>
    <?php
}
?>
</center>
</body>
</html>