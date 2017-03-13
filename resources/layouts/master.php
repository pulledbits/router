<?php $layout = $this->layout('html5'); ?>
<?php $layout->section('title', 'Teach - ' . html_entity_decode($this->harvest('title'))); ?>
<?php $layout->section('head'); ?>
<link rel="stylesheet" type="text/css" href="/css/lesplan.css">
<?php $layout->section('body'); ?>
<?= $this->harvest('content'); ?>