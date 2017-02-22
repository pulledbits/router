<?php $layout = $this->layout('master'); ?>

<?php $layout->section('title', 'Welcome'); ?>

<?php $layout->section('content'); ?>
<header>
	<h1>Overzicht contactmomenten</h1>
    <ul class="horizontal-menu">
        <li><a href="/contactmoment/import" target="_blank">Importeer contactmomenten</a></li>
    </ul>
</header>

<nav>
	<ul class="horizontal-menu">
		<?php foreach ($modules as $module): ?>
		<li><a href="#<?= $module->naam ?>"><?= $module->naam ?></a></li>
		<?php endforeach;?>
	</ul>
</nav>
<section>
    <?php $this->sub('contactmomenten')->render([
		'caption'=> 'Contactmomenten vandaag',
		'contactmomenten' => $contactmomenten
	]); ?>
</section>

<?php foreach ($modules as $module): ?>
<section>
	<a name="<?= $module->naam ?>"></a>
	<h2><?= $module->naam ?></h2>
    <?php $this->sub('contactmomenten')->render([
		'caption'=> 'Contactmomenten',
		'contactmomenten' => $module->read("contactmoment_module", ["module_id" => "id"])
	]); ?>
</section>
<?php endforeach; ?>