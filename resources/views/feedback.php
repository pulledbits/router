<?php $layout = $this->layout('html5'); ?>
<?php $layout->section('title', 'Feedback'); ?>
<?php $layout->section('head'); ?>
<meta charset="utf-8">
<meta http-equiv="refresh" content="5">
<style>
    body {
        font-family: arial, sans-serif;
        text-align: center;
    }
</style>

<?php $layout->section('body'); ?>
<body>
<h1>Feedback-o-meter</h1>
<?php $url = $this->url('/feedback/' . $contactmoment->id . '/supply'); ?>
<img src="<?=$this->url('/rating/%s', $contactmoment->id); ?>" width="500" height="100"
     style="margin: 25px 0;"/></br> <img
        src="<?=$this->url('/qr?data=%s', $url); ?>"
        width="400" height="400"/>
<p><?=$url; ?></p>
</body>
</html>