<?php
namespace Teach\Adapters\HTML;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderTemplate()
    {
        $template = tempnam(sys_get_temp_dir(), 'tpl');
        file_put_contents($template, '<?php return function (\Teach\Adapters\HTML\Factory $factory) { return "<a>Hello World</a>"; };');
        $object = new Factory();
        $html = $object->renderTemplate($template);
        $this->assertEquals('<a>Hello World</a>', $html);
    }

    public function testRenderTemplateWithParameters()
    {
        $template = tempnam(sys_get_temp_dir(), 'tpl');
        file_put_contents($template, '<?php return function (\Teach\Adapters\HTML\Factory $factory, $a, $b) { return "<$a>$b</$a>"; };');
        $object = new Factory();
        $html = $object->renderTemplate($template, "a", "Hello World");
        $this->assertEquals('<a>Hello World</a>', $html);
    }

    public function testCreateElement()
    {
        $object = new Factory();
        $html = $object->createElement('a', [], [
            'Hello World'
        ])->render();
        $this->assertEquals('<a>Hello World</a>', $html);
    }

    public function testCreateText()
    {
        $object = new Factory();
        $html = $object->createText('Hello World')->render();
        $this->assertEquals('Hello World', $html);
    }

    public function testCreateElementWithMultipleChildren()
    {
        $object = new Factory();
        $html = $object->createElement('a', [], [
            'Hello World',
            [
                'li',
                [
                    'src' => "bla.gif"
                ],
                []
            ]
        ])->render();
        $this->assertEquals('<a>Hello World<li src="bla.gif"></li></a>', $html);
    }

    public function testMakeHTML()
    {
        $object = new Factory();
        $html = $object->makeHTML([
            'Hello World',
            [
                'li',
                [
                    'src' => "bla.gif"
                ],
                []
            ]
        ]);
        $this->assertEquals('Hello World<li src="bla.gif"></li>', $html);
    }

    public function testMakeHTMLWithTextOnlyElement()
    {
        $object = new Factory();
        $html = $object->makeHTML([
            'Hello World',
            [
                'a', [], ['Hello Hello']
            ]
        ]);
        $this->assertEquals('Hello World<a>Hello Hello</a>', $html);
    }
    
    public function testRenderTable()
    {
        $object = new Factory();
        
        $expectedHTML = '<table><caption>Activerende opening</caption><tr><th>inhoud</th><td id="inhoud">bladiebla</td><th>werkvorm</th><td id="werkvorm">bladiebla</td></tr><tr><th>inhoud</th><td id="inhoud" colspan="3"><ul><li>A</li><li>B</li><li>C</li></ul></td></tr></table>';
        $this->assertEquals($expectedHTML, $object->makeTable('Activerende opening', [
            [
                "inhoud" => "bladiebla",
                "werkvorm" => "bladiebla"
            ],
            [
                "inhoud" => ['A', 'B', 'C']
            ]
        ])->render());
    }

    public function testMakeTableNoCaption()
    {
        $object = new Factory();
        $html = $object->makeTable(null, [
            [
                "inhoud" => "bladiebla"
            ]
        ])->render();
        $this->assertEquals('<table><tr><th>inhoud</th><td id="inhoud">bladiebla</td></tr></table>', $html);
    }

    public function testMakeTableRow()
    {
        $object = new Factory();
        $html = $object->makeTableRow(2, [
            "inhoud" => "bladiebla"
        ])->render();
        $this->assertEquals('<tr><th>inhoud</th><td id="inhoud">bladiebla</td></tr>', $html);
    }

    public function testMakeTableRowWithUnorderedList()
    {
        $object = new Factory();
        $html = $object->makeTableRow(2, [
            "inhoud" => ['A', 'B', 'C']
        ])->render();
        $this->assertEquals('<tr><th>inhoud</th><td id="inhoud"><ul><li>A</li><li>B</li><li>C</li></ul></td></tr>', $html);
    }

    public function testMakeTableRowWithSpanningCell()
    {
        $object = new Factory();
        $html = $object->makeTableRow(4, [
            "inhoud" => "bladiebla"
        ])->render();
        $this->assertEquals('<tr><th>inhoud</th><td id="inhoud" colspan="3">bladiebla</td></tr>', $html);
    }
    
    public function testRenderUnorderedList()
    {
        $object = new Factory();
        $expectedHTML = '<ul><li>A</li><li>B</li><li>C</li></ul>';
        $this->assertEquals($expectedHTML, $object->renderUnorderedList([
            "A",
            "B",
            "C"
        ]));
    }

    public function testMakeUnorderedList()
    {
        $object = new Factory();
        $html = $object->makeUnorderedList([
            "A",
            "B",
            "C"
        ]);
        $this->assertEquals('ul', $html[Factory::TAG]);
        $this->assertEquals('li', $html[Factory::CHILDREN][0][Factory::TAG]);
        $this->assertEquals('A', $html[Factory::CHILDREN][0][Factory::CHILDREN][0]);
        $this->assertEquals('li', $html[Factory::CHILDREN][1][Factory::TAG]);
        $this->assertEquals('B', $html[Factory::CHILDREN][1][Factory::CHILDREN][0]);
        $this->assertEquals('li', $html[Factory::CHILDREN][2][Factory::TAG]);
        $this->assertEquals('C', $html[Factory::CHILDREN][2][Factory::CHILDREN][0]);
    }

}
