<?php
namespace Teach\Interactions\Web;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testDocument()
    {
        $parts = new Document\Parts(new Lesplan\Fase('2', "Introductie", new Document\Parts()), new Lesplan\Fase('2', "Kern", new Document\Parts()), new Lesplan\Fase('2', "Afsluiting", new Document\Parts()));
        
        $object = new Document("Lesplan Programmeren 1", "HBO-informatica (voltijd)", $parts);
        
        $html = $object->document(\Test\Helper::implementDocumenter());
        $this->assertEquals('fpLesplan Programmeren 1:HBO-informatica (voltijd)section2:Introductie...section2:Kern...section2:Afsluiting...', $html);
    }
}
