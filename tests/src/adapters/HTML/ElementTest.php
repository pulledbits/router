<?php
namespace Teach\Adapters\HTML;

class ElementTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $object = new Element("table");
        $html = $object->render();
        $this->assertEquals('<table></table>', $html);
    }

    public function testRenderAttributes()
    {
        $object = new Element("table");
        $object->attribute("class", "twin-cell");
        $html = $object->render();
        $this->assertEquals('<table class="twin-cell"></table>', $html);
    }
}
