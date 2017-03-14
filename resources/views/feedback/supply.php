<?php $layout = $this->layout('html5'); ?>
<?php $layout->section('title', 'Feedback'); ?>
<?php $layout->section('head'); ?>
<meta charset="utf-8">
<style>
    body {
        font-family: arial, sans-serif;
        text-align: center;
    }
</style>
<?php $layout->section('body'); ?>
<h1>Hoeveel sterren?</h1>
<?php
for ($i = 0; $i < 5; $i ++) {
    ?><a href="?rating=<?= rawurldecode($i + 1) ?>"><img src="<?= $this->star($i) ?>" width="100" /></a><?php
}
if ($rating !== null) {
    ?>
    <form action="supply" method="post">
        <input type="hidden" name="__csrf_value" value="<?= $csrf_value ?>">
        <h1>Waarom?</h1>
        <input type="hidden" name="rating"
               value="<?= $rating ?>" />
        <textarea rows="5" cols="75" name="explanation"><?= $explanation ?></textarea>
        <p>
            <input type="submit" value="Verzenden!" />
        </p>
    </form>
    <?php
}