<?php
return function(\Teach\Adapters\HTML\Factory $factory, \Teach\Interactors\Web\Lesplan $lesplan) {
    $variables = $lesplan->provideTemplateVariables([
        "title",
        "subtitle"
    ]);
    
    $lines = [];
    $lines[] = "<!DOCTYPE html>";
    $lines[] = "<html>";
    $lines[] = $factory->renderTemplate(__DIR__ . DIRECTORY_SEPARATOR . "head.php");
    $lines[] = "<body>";
    $lines[] = $factory->renderTemplate(__DIR__ . DIRECTORY_SEPARATOR . "header.php", $variables["title"], $variables["subtitle"]);
    $lines[] = $factory->makeHTML($lesplan->generateLayout($factory));
    $lines[] = "</body>";
    $lines[] = "</html>";
    return join(PHP_EOL, $lines);
};