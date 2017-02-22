<?php
namespace ActiveRecord;

interface PHPView {


    public function render(array $variables);
    public function layout(string $layoutIdentifier);

}