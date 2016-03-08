<?php
namespace Teach\Domain;

class Lesplan implements \Teach\Interactors\InteractableInterface
{

    /**
     *
     * @var Factory
     */
    private $factory;

    /**
     *
     * @var array contactmoment record
     */
    private $contactmoment;

    public function __construct(Factory $factory, array $contactmoment)
    {
        $this->factory = $factory;
        $this->contactmoment = $contactmoment;
    }

    /**
     *
     * @return array
     */
    private function getBeginsituatie()
    {
        return [
            'doelgroep' => [
                'beschrijving' => $this->contactmoment['doelgroep_beschrijving'],
                'ervaring' => $this->contactmoment['doelgroep_ervaring'],
                'grootte' => $this->contactmoment['doelgroep_grootte'] . ' personen'
            ],
            'starttijd' => date('H:i', strtotime($this->contactmoment['starttijd'])),
            'eindtijd' => date('H:i', strtotime($this->contactmoment['eindtijd'])),
            'duur' => $this->contactmoment['duur'],
            'ruimte' => $this->contactmoment['ruimte'],
            'overige' => $this->contactmoment['opmerkingen']
        ];
    }

    /**
     * 
     * @param \Teach\Interactors\Web\Lesplan\Factory $factory
     * @return \Teach\Interactors\LayoutableInterface
     */
    public function interact(\Teach\Interactors\Web\Lesplan\Factory $factory): \Teach\Interactors\LayoutableInterface
    {
        $introductie = $factory->createIntroductie(
            $factory->createActiviteit("Activerende opening", $this->factory->getActiviteit($this->contactmoment['activerende_opening_id'])), 
            $factory->createActiviteit("Focus", $this->factory->getActiviteit($this->contactmoment['focus_id'])),
            $factory->createActiviteit("Voorstellen", $this->factory->getActiviteit($this->contactmoment['voorstellen_id'])),
            $factory->createActiviteit("Kennismaken", $this->factory->getActiviteit($this->contactmoment['kennismaken_id'])),
            $factory->createActiviteit("Terugblik", $this->factory->getActiviteit($this->contactmoment['terugblik_id']))
        );
        
        $kernDefinition = $this->factory->getKern($this->contactmoment['lesplan_id']);
        $kern = $factory->createFase("Kern");
        $themaCount = 0;
        foreach ($kernDefinition as $themaIdentifier => $themaDefinition) {
            $thema = $factory->createThema('Thema ' . (++$themaCount) . ': ' . $themaIdentifier, $themaDefinition);
            $kern->addOnderdeel($thema);
        }
        
        $contactmoment = $this->factory->createContactmoment($this->getBeginsituatie(), $this->contactmoment['lesplan_id']);

        $afsluiting = $this->factory->createAfsluiting($this->contactmoment['huiswerk_id'], $this->contactmoment['evaluatie_id'], $this->contactmoment['pakkend_slot_id']);
        
        return $factory->createLesplan($this->contactmoment['opleiding'], $this->contactmoment['vak'], $this->contactmoment['les'], $contactmoment->interact($factory), $introductie, $kern, $afsluiting->interact($factory));
    }
}