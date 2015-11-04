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
$lesplan = new \Teach\Adapters\Web\Lesplan($lesplanDefinition['vak'], $lesplanDefinition['les'], $beginsituatie, $lesplanDefinition['media'], $introductie, array_keys($lesplanDefinition['Kern']));
print $HTMLfactory->makeHTMLFrom($lesplan);

$kern = $lesplanFactory->createFase('Kern');
$themaCounter = 1;
foreach ($lesplanDefinition['Kern'] as $themaIdentifier => $themaDefinition) {
    $thema = $lesplanFactory->createThema('Thema ' . $themaCounter . ': ' . $themaIdentifier);
    foreach ($themaDefinition as $activiteitIdentifier => $activiteitDefinition) {
        $thema->addActiviteit($lesplanFactory->createActiviteit($activiteitIdentifier, $activiteitDefinition));
    }
    $kern->addOnderdeel($thema);
    $themaCounter ++;
}
print $HTMLfactory->makeHTMLFrom($kern);

$afsluiting = $lesplanFactory->createFase('Afsluiting');
foreach ($lesplanDefinition['Introductie'] as $activiteitIdentifier => $activiteitDefinition) {
    $activiteit = $lesplanFactory->createActiviteit($activiteitIdentifier, $activiteitDefinition);
    $afsluiting->addOnderdeel($activiteit);
}
print $HTMLfactory->makeHTMLFrom($afsluiting);

?>
</body>
</html>