<?php
namespace Teach\Adapters\Web\Lesplan;

final class Beginsituatie implements \Teach\Adapters\HTML\LayoutableInterface
{
    private $opleiding;
    
    private $beginsituatie;

    public function __construct($opleiding, array $beginsituatie)
    {
        $this->opleiding = $opleiding;
        $this->beginsituatie = $beginsituatie;
    }

    /**
     *
     * @return array
     */
    public function generateHTMLLayout()
    {
        
        return [
            ['h3', 'Beginsituatie'],
            [
                'table',
                [
                    'class' => 'two-columns'
                ],
                [
                    [
                        'tr',
                        [],
                        [
                            [
                                'th',
                                'doelgroep'
                            ],
                            [
                                'td',
                                $this->beginsituatie['doelgroep']['beschrijving']
                            ],
                            [
                                'th',
                                'opleiding'
                            ],
                            [
                                'td',
                                $this->opleiding
                            ]
                        ]
                    ],
                    [
                        'tr',
                        [],
                        [
                            [
                                'th',
                                'ervaring'
                            ],
                            [
                                'td',
                                $this->beginsituatie['doelgroep']['ervaring']
                            ],
                            [
                                'th',
                                'groepsgrootte'
                            ],
                            [
                                'td',
                                $this->beginsituatie['doelgroep']['grootte']
                            ]
                        ]
                    ],
                    [
                        'tr',
                        [],
                        [
                            [
                                'th',
                                'tijd'
                            ],
                            [
                                'td',
                                [
                                    'colspan' => '3'
                                ],
                                [
                                    'van <strong>' . $this->beginsituatie['starttijd'] . '</strong> tot <strong>' . $this->beginsituatie['eindtijd'] . '</strong> (' . $this->beginsituatie['duur'] . ' minuten)'
                                ]
                            ]
                        ]
                    ],
//                     [
//                         'tr',
//                         [],
//                         [
//                             [
//                                 'th',
//                                 'inhoud'
//                             ],
//                             [
//                                 'td',
//                                 [
//                                     'colspan' => '3'
//                                 ],
//                                 $inhoudChildren
//                             ]
//                         ]
//                     ]
                ]
            ]
        ]
        ;
    }
}