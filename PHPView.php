<?php
namespace pulledbits\View;

interface Template {

    public function render(array $variables);

}

interface Layout {

    public function section(string $sectionIdentifier, string $content = null);
    public function render();

}