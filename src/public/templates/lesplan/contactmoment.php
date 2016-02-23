<?php
return function(\Teach\Adapters\HTML\Factory $factory, string $title, \Teach\Interactors\Web\Lesplan\Contactmoment $contactmoment) {
    $variables = $contactmoment->provideTemplateVariables([
        'doelgroep',
        'groepsgrootte',
        'tijd',
        'ruimte',
        'overige',
        'media',
        'leerdoelen'
    ]);
    
    return '<section><h2>' . htmlentities($title) . '</h2><h3>Beginsituatie</h3>' . $factory->renderTable($title, [
                [
                    'doelgroep' => $variables['doelgroep']['beschrijving'],
                    'ervaring' => $variables['doelgroep']['ervaring']
                ],
                [
                    'groepsgrootte' => $variables['groepsgrootte']
                ],
                [
                    'tijd' => 'van ' . $variables['tijd']
                ],
                [
                    'ruimte' => $variables['ruimte']
                ],
                [
                    'overige' => $variables['overige']
                ]
    ]) . '<h3>Media</h3>' . $factory->renderUnorderedList($variables['media']) . '<h3>Leerdoelen</h3><p>Na afloop van de les kan de student:</p>' . $factory->renderUnorderedList($variables['leerdoelen']) . '</section>';
};