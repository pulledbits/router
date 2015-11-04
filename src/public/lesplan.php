<?php
if (array_key_exists('lesplan', $_GET) === false) {
    http_response_code(400);
    exit();
}

$applicationBootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
$lesplanLocator = $applicationBootstrap();

$lesplanDefinition = $lesplanLocator($_GET['lesplan']);
if ($lesplanDefinition === null) {
    http_response_code(404);
    exit();
}

$HTMLfactory = new \Teach\Adapters\HTML\Factory();
$lesplanFactory = new \Teach\Adapters\Web\Lesplan\Factory();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lesplan</title>
<link rel="stylesheet" type="text/css" href="lesplan.css">
</head>
<body>
<?php
$beginsituatie = $lesplanFactory->createBeginsituatie($lesplanDefinition['opleiding'], $lesplanDefinition['Beginsituatie']);
$introductie = $lesplanFactory->createFase('Introductie');
foreach ($lesplanDefinition['Introductie'] as $activiteitIdentifier => $activiteitDefinition) {
    $activiteit = $lesplanFactory->createActiviteit($activiteitIdentifier, $activiteitDefinition);
    $introductie->addOnderdeel($activiteit);
}

$themaCounter = 1;
$kern = $lesplanFactory->createFase('Kern');
foreach ($lesplanDefinition['Kern'] as $themaIdentifier => $themaDefinition) {
    $thema = $lesplanFactory->createThema('Thema ' . $themaCounter . ': ' . $themaIdentifier);
    foreach ($themaDefinition as $activiteitIdentifier => $activiteitDefinition) {
        $thema->addActiviteit($lesplanFactory->createActiviteit($activiteitIdentifier, $activiteitDefinition));
    }
    $kern->addOnderdeel($thema);
    $themaCounter++;
}
$introductie->chainTo($kern);

$afsluiting = $lesplanFactory->createFase('Afsluiting');
foreach ($lesplanDefinition['Afsluiting'] as $activiteitIdentifier => $activiteitDefinition) {
    $activiteit = $lesplanFactory->createActiviteit($activiteitIdentifier, $activiteitDefinition);
    $afsluiting->addOnderdeel($activiteit);
}
$kern->chainTo($afsluiting);

$lesplan = new \Teach\Adapters\Web\Lesplan($lesplanDefinition['vak'], $lesplanDefinition['les'], $beginsituatie, $lesplanDefinition['media'], array_keys($lesplanDefinition['Kern']), $introductie);
print $HTMLfactory->makeHTMLFrom($lesplan);

?>
</body>
</html>