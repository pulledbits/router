<?php
namespace Teach\Adapters\Web\Lesplan;

use \Teach\Adapters\HTML\Factory as HTMLFactory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateActiviteit()
    {
        $object = new Factory();
        $layout = $object->createActiviteit("Activerende opening", [
            'inhoud' => '',
            'werkvorm' => "",
            'organisatievorm' => "plenair",
            'werkvormsoort' => "ijsbreker",
            'tijd' => "",
            'intelligenties' => [
                \Teach\Adapters\Web\Lesplan\Activiteit::MI_VERBAAL_LINGUISTISCH,
                \Teach\Adapters\Web\Lesplan\Activiteit::MI_VISUEEL_RUIMTELIJK,
                \Teach\Adapters\Web\Lesplan\Activiteit::MI_INTERPERSOONLIJK,
                \Teach\Adapters\Web\Lesplan\Activiteit::MI_INTRAPERSOONLIJK
            ]
        ])->generateHTMLLayout();
        $this->assertEquals("Activerende opening", $layout[0][HTMLFactory::CHILDREN][0][HTMLFactory::TEXT]);
    }
    

    public function testCreateThema()
    {
        $object = new Factory();
        $layout = $object->createThema("Thema caption here", [
            "Activerende opening" => [
                'inhoud' => 'bla die bla',
                'werkvorm' => "",
                'organisatievorm' => "plenair",
                'werkvormsoort' => "ijsbreker",
                'tijd' => "",
                'intelligenties' => [
                    \Teach\Adapters\Web\Lesplan\Activiteit::MI_VERBAAL_LINGUISTISCH,
                    \Teach\Adapters\Web\Lesplan\Activiteit::MI_VISUEEL_RUIMTELIJK,
                    \Teach\Adapters\Web\Lesplan\Activiteit::MI_INTERPERSOONLIJK,
                    \Teach\Adapters\Web\Lesplan\Activiteit::MI_INTRAPERSOONLIJK
                ]
            ]
        ])->generateHTMLLayout();
        $this->assertEquals("Thema caption here", $layout[0][HTMLFactory::CHILDREN][0][HTMLFactory::TEXT]);

        $row = $layout[0][HTMLFactory::CHILDREN][1][HTMLFactory::CHILDREN][4];
        $this->assertEquals("tr", $row[HTMLFactory::TAG]);
        $this->assertEquals("th", $row[HTMLFactory::CHILDREN][0][HTMLFactory::TAG]);
        $this->assertEquals("inhoud", $row[HTMLFactory::CHILDREN][0][HTMLFactory::TEXT]);
        $this->assertEquals("td", $row[HTMLFactory::CHILDREN][1][HTMLFactory::TAG]);
        $this->assertEquals('3', $row[HTMLFactory::CHILDREN][1][HTMLFactory::ATTRIBUTES]['colspan']);
        $this->assertEquals('bla die bla', $row[HTMLFactory::CHILDREN][1][HTMLFactory::CHILDREN][0]);
    }


    public function testCreateFase()
    {
        $object = new Factory();
        $layout = $object->createFase("Kern")->generateHTMLLayout();
        $this->assertEquals("Kern", $layout[0][HTMLFactory::CHILDREN][0][HTMLFactory::TEXT]);
    }

    public function testCreateFaseWithActiviteiten()
    {
        $object = new Factory();
        $layout = $object->createFaseWithActiviteiten("Introductie", [
            "Activerende opening" => [
                'inhoud' => 'bla die bla',
                'werkvorm' => "",
                'organisatievorm' => "plenair",
                'werkvormsoort' => "ijsbreker",
                'tijd' => "",
                'intelligenties' => [
                    \Teach\Adapters\Web\Lesplan\Activiteit::MI_VERBAAL_LINGUISTISCH,
                    \Teach\Adapters\Web\Lesplan\Activiteit::MI_VISUEEL_RUIMTELIJK,
                    \Teach\Adapters\Web\Lesplan\Activiteit::MI_INTERPERSOONLIJK,
                    \Teach\Adapters\Web\Lesplan\Activiteit::MI_INTRAPERSOONLIJK
                ]
            ]
        ])->generateHTMLLayout();
        $this->assertEquals("Introductie", $layout[0][HTMLFactory::CHILDREN][0][HTMLFactory::TEXT]);
        
        $row = $layout[0][HTMLFactory::CHILDREN][1][HTMLFactory::CHILDREN][4];
        $this->assertEquals("tr", $row[HTMLFactory::TAG]);
        $this->assertEquals("th", $row[HTMLFactory::CHILDREN][0][HTMLFactory::TAG]);
        $this->assertEquals("inhoud", $row[HTMLFactory::CHILDREN][0][HTMLFactory::TEXT]);
        $this->assertEquals("td", $row[HTMLFactory::CHILDREN][1][HTMLFactory::TAG]);
        $this->assertEquals('3', $row[HTMLFactory::CHILDREN][1][HTMLFactory::ATTRIBUTES]['colspan']);
        $this->assertEquals('bla die bla', $row[HTMLFactory::CHILDREN][1][HTMLFactory::CHILDREN][0]);
    }
    
    public function testCreateKern()
    {
        $object = new Factory();
        $layout = $object->createKern([
            "Thema caption here" => [
                "Activerende opening" => [
                    'inhoud' => 'bla die bla',
                    'werkvorm' => "",
                    'organisatievorm' => "plenair",
                    'werkvormsoort' => "ijsbreker",
                    'tijd' => "",
                    'intelligenties' => [
                        \Teach\Adapters\Web\Lesplan\Activiteit::MI_VERBAAL_LINGUISTISCH,
                        \Teach\Adapters\Web\Lesplan\Activiteit::MI_VISUEEL_RUIMTELIJK,
                        \Teach\Adapters\Web\Lesplan\Activiteit::MI_INTERPERSOONLIJK,
                        \Teach\Adapters\Web\Lesplan\Activiteit::MI_INTRAPERSOONLIJK
                    ]
                ]
            ]
        ])->generateHTMLLayout();
        $this->assertEquals("Kern", $layout[0][HTMLFactory::CHILDREN][0][HTMLFactory::TEXT]);

        $this->assertEquals("Thema 1: Thema caption here", $layout[0][HTMLFactory::CHILDREN][1][HTMLFactory::CHILDREN][0][HTMLFactory::TEXT]);
        
        $row = $layout[0][HTMLFactory::CHILDREN][1][HTMLFactory::CHILDREN][1][HTMLFactory::CHILDREN][4];
        $this->assertEquals("tr", $row[HTMLFactory::TAG]);
        $this->assertEquals("th", $row[HTMLFactory::CHILDREN][0][HTMLFactory::TAG]);
        $this->assertEquals("inhoud", $row[HTMLFactory::CHILDREN][0][HTMLFactory::TEXT]);
        $this->assertEquals("td", $row[HTMLFactory::CHILDREN][1][HTMLFactory::TAG]);
        $this->assertEquals('3', $row[HTMLFactory::CHILDREN][1][HTMLFactory::ATTRIBUTES]['colspan']);
        $this->assertEquals('bla die bla', $row[HTMLFactory::CHILDREN][1][HTMLFactory::CHILDREN][0]);
    }


    public function testCreateBeginsituatie()
    {
        $object = new Factory();
        $layout = $object->createBeginsituatie("Opleding", [
            'doelgroep' => [
                'beschrijving' => 'eerstejaars HBO-studenten',
                'ervaring' => 'geen', // <!-- del>veel</del>, <del>redelijk veel</del>, <del>weinig</del>, -->geen
                'grootte' => '16 personen'
            ],
            'starttijd' => '08:45',
            'eindtijd' => '10:20',
            'duur' => '95',
            'ruimte' => 'beschikking over vaste computers',
            'overige' => 'nvt'
        ])->generateHTMLLayout();
        $this->assertEquals('h3', $layout[0][HTMLFactory::TAG]);
        $this->assertEquals("Beginsituatie", $layout[0][HTMLFactory::TEXT]);
    }
}
