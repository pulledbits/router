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
<?php $layout->section('body');
$this->rateForm($rating, $explanation);