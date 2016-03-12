<?php
namespace Teach\Domain\Lesplan;

class Kern implements \Teach\Interactions\Interactable
{

    /**
     *
     * @var array
     */
    private $themas;

    public function __construct(array $themas)
    {
        $this->themas = $themas;
    }

    /**
     *
     * @param \Teach\Interactions\Web\Lesplan\Factory $factory            
     * @return \Teach\Interactions\Documentable
     */
    public function interact(\Teach\Interactions\Web\Lesplan\Factory $factory): \Teach\Interactions\Documentable
    {
        $themaCount = 0;
        $themas = [];
        foreach ($this->themas as $themaIdentifier => $themaDefinition) {
            $activiteiten = [];
            foreach ($themaDefinition as $activiteitIdentifier => $activiteitDefinition) {
                $activiteiten[] = $factory->createActiviteit($activiteitIdentifier, $activiteitDefinition);
            }
            $themas[] = $factory->createFase('Thema ' . (++ $themaCount) . ': ' . $themaIdentifier, $factory->createDocumentParts(...$activiteiten));
        }
        return $factory->createFase("Kern", $factory->createDocumentParts(...$themas));
    }
}