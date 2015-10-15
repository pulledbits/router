<?php
$applicationBootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
$applicationBootstrap();

$introductie = array(
    "Activerende opening" => array(
        'inhoud' => 'Scené uit de matrix tonen waarop wordt gezegd: "I don\'t even see the code". Wie kent deze film? Een ervaren programmeur zal een vergelijkbaar gevoel hebben bij code: programmeren is een visualisatie kunnen uitdrukken in code en vice versa.',
        'werkvorm' => "film",
        'organisatievorm' => "plenair",
        'werkvormsoort' => "ijsbreker",
        'tijd' => "5",
        'intelligenties' => array(
            "verbaal-linguistisch",
            "visueel-ruimtelijk",
            "interpersoonlijk",
            "intrapersoonlijk"
        )
    ),
    "Focus" => array(
        "inhoud" => "Visie, Leerdoelen, Programma, Afspraken",
        "werkvorm" => "presentatie",
        "organisatievorm" => "plenair",
        "werkvormsoort" => "docent gecentreerd",
        "tijd" => "4",
        "intelligenties" => array(
            "verbaal-linguistisch",
            "logisch-mathematisch",
            "interpersoonlijk"
        )
    ),
    "Voorstellen" => array(
        "inhoud" => "Voorstellen Docent",
        "werkvorm" => "presentatie",
        "organisatievorm" => "plenair",
        "werkvormsoort" => "docent gecentreerd",
        "tijd" => "4",
        "intelligenties" => array(
            "verbaal-linguistisch",
            "logisch-mathematisch",
            "interpersoonlijk"
        )
    )
);

function renderFase($naam, array $werkvorm)
{
    $beschikbareIntelligenties = array(
        "verbaal-linguistisch" => "VL",
        "logisch-mathematisch" => "LM",
        "visueel-ruimtelijk" => "VR",
        "muzikaal-ritmisch" => "MR",
        "lichamelijk-kinesthetisch" => "LK",
        "naturalistisch" => "N",
        "interpersoonlijk" => "IR",
        "intrapersoonlijk" => "IA"
    );
    
    ?>
<table class="two-columns">
	<caption><?=htmlentities($naam); ?></caption>
	<tr>
		<th>werkvorm</th>
		<td><?=htmlentities($werkvorm['werkvorm']); ?></td>
		<th>organisatievorm</th>
		<td><?=htmlentities($werkvorm['organisatievorm']); ?></td>
	</tr>
	<tr>
		<th>tijd</th>
		<td><?=htmlentities($werkvorm['tijd']); ?> minuten</td>
		<th>soort werkvorm</th>
		<td><?=htmlentities($werkvorm['werkvormsoort']); ?></td>
	</tr>
	<tr>
		<th>intelligenties</th>
		<td colspan="3">
			<ul class="meervoudige-intelligenties">
					<?php
    foreach ($beschikbareIntelligenties as $beschikbareIntelligentieIdentifier => $beschikbareIntelligentie) {
        $selected = in_array($beschikbareIntelligentieIdentifier, $werkvorm['intelligenties']);
        ?><li
					id="<?= htmlentities($beschikbareIntelligentieIdentifier); ?>"
					<?=($selected ? ' class="selected"' : '');?>><?=htmlentities($beschikbareIntelligentie); ?></li><?php
    }
    ?>
				</ul>
		</td>
	</tr>
	<tr>
		<th>inhoud</th>
		<td colspan="3"><?=htmlentities($werkvorm['inhoud']); ?></td>
	</tr>
</table>
<?php
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lesplan</title>
<style>
body {
	font-family: sans-serif;
	line-height: 1.5em;
}

body header {
	text-align: center;
}

body table {
	width: 210mm;
	border-spacing: 0;
}

body section h3 {
	color: #ff6600;
}

table caption {
	color: #aa6600;
}

table td, table th {
	padding: 5px;
}

table th {
	text-align: right;
	background-color: #f6f6f6;
	vertical-align: top;
	width: 20%;
}

table.two-columns th {
	width: 10%;
}

ul.meervoudige-intelligenties {
	list-style: none;
	margin: 0;
	padding: 0;
}

ul.meervoudige-intelligenties li {
	float: left;
	padding: 0 5px;
	color: #d0d0d0;
}

ul.meervoudige-intelligenties li.selected {
	color: #000000;
}

hr {
	border: 0;
	height: 1px;
	background: #333;
}

@media print {
	body header, section {
		page-break-after: always;
	}
}
</style>
</head>
<body>
	<header>
		<h1>Lesplan programmeren</h1>
		<h2>Blok 1 / Week 1 / Les 1</h2>
	</header>
	<section>
		<h3>Beginsituatie</h3>
		<table class="two-columns">
			<tr>
				<th>doelgroep</th>
				<td>eerstejaars HBO-studenten</td>
				<th>opleiding</th>
				<td>HBO-informatica (<del>deeltijd</del>/voltijd)
				</td>
			</tr>
			<tr>
				<th>ervaring</th>
				<td><del>veel</del>, <del>redelijk veel</del>, <del>weinig</del>,
					geen</td>
				<th>groepsgrootte</th>
				<td>ca. 16 personen</td>
			</tr>
			<tr>
				<th>tijd</th>
				<td>van <strong>8:45</strong> tot <strong>10:20</strong> (45
					minuten)
				</td>
				<th>ruimte</th>
				<td>beschikking over vaste computers</td>
			</tr>
			<tr>
				<th>overige</th>
				<td colspan="3">nvt</td>
			</tr>
		</table>
		<h3>Benodigde media</h3>
		<ul>
			<li>filmfragment matrix</li>
			<li>rode en groene briefjes/post-its voor feedback</li>
			<li>presentatie</li>
			<li>voorbeeldproject voor aanvullende feedback</li>
		</ul>
		<h3>Leerdoelen</h3>
		<p>Na afloop van de les kan de student:</p>
		<ol>
			<li>Zelfstandig eclipse installeren</li>
			<li>Java-code lezen en uitleggen wat er gebeurt</li>
		</ol>
	</section>
	<hr />
	<section>
		<h3>Introductie</h3>
		<?php
foreach ($introductie as $faseIdentifier => $fase) {
    renderFase($faseIdentifier, $fase);
}
?>
    </section>
</body>
</html>