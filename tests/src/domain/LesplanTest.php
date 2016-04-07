<?php
namespace Teach\Domain;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-14 at 13:44:20.
 */
class LesplanTest extends \Teach\DomainTest
{
    public function testDocument()
    {
        $factory = new Factory(new \Teach\Interactions\Database(self::$pdo));
        $object = $factory->createLesplan('1');
        $html = $object->document(\Test\Helper::implementDocumenter());
        $this->assertEquals('<html><head><title>Lesplan PROG1</title></head><body>fpLesplan PROG1:HBO-informatica (voltijd)section2:Week1a...3:Beginsituatie...: a:5:{i:0;a:2:{s:9:"doelgroep";s:25:"eerstejaars HBO-studenten";s:8:"ervaring";s:4:"geen";}i:1;a:1:{s:13:"groepsgrootte";s:11:"16 personen";}i:2;a:1:{s:4:"tijd";s:32:"van 08:45 tot 10:20 (95 minuten)";}i:3;a:1:{s:6:"ruimte";s:32:"beschikking over vaste computers";}i:4;a:1:{s:7:"overige";s:3:"nvt";}}...3:Media...ul: a:5:{i:0;s:19:"filmfragment matrix";i:1;s:49:"countdown timer voor toepassingsfases (optioneel)";i:2;s:52:"voorbeeld IKEA-handleiding + uitgewerkte pseudo-code";i:3;s:46:"rode en groene briefjes/post-its voor feedback";i:4;s:42:"voorbeeldproject voor aanvullende feedback";}...3:Leerdoelen...<p>Na afloop van de les kan de student:</p>...ul: a:2:{i:0;s:31:"Zelfstandig eclipse installeren";i:1;s:43:"Java-code lezen en uitleggen wat er gebeurt";}section2:Introductie...Activerende opening: a:4:{i:0;a:2:{s:8:"werkvorm";s:4:"film";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"5 minuten";s:14:"soort werkvorm";s:9:"ijsbreker";}i:2;a:1:{s:14:"intelligenties";a:4:{i:0;s:2:"VL";i:1;s:2:"VR";i:2;s:2:"IR";i:3;s:2:"IA";}}i:3;a:1:{s:6:"inhoud";a:4:{i:0;s:56:"Scené uit de matrix tonen waarop wordt gezegd: "I don\'t";i:1;s:67:"				even see the code". Wie kent deze film? Een ervaren programmeur";i:2;s:69:"				zal een vergelijkbaar gevoel hebben bij code: programmeren is een";i:3;s:57:"				visualisatie kunnen uitdrukken in code en vice versa.";}}}...Focus: a:4:{i:0;a:2:{s:8:"werkvorm";s:11:"presentatie";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"3 minuten";s:14:"soort werkvorm";s:18:"docent gecentreerd";}i:2;a:1:{s:14:"intelligenties";a:3:{i:0;s:2:"VL";i:1;s:2:"LM";i:2;s:2:"IR";}}i:3;a:1:{s:6:"inhoud";a:1:{i:0;s:39:"Visie, Leerdoelen, Programma, Afspraken";}}}...Voorstellen: a:4:{i:0;a:2:{s:8:"werkvorm";s:11:"presentatie";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"1 minuten";s:14:"soort werkvorm";s:18:"docent gecentreerd";}i:2;a:1:{s:14:"intelligenties";a:3:{i:0;s:2:"VL";i:1;s:2:"LM";i:2;s:2:"IR";}}i:3;a:1:{s:6:"inhoud";a:1:{i:0;s:18:"Voorstellen Docent";}}}...Kennismaken: a:4:{i:0;a:2:{s:8:"werkvorm";s:8:"onbekend";s:15:"organisatievorm";s:3:"nvt";}i:1;a:2:{s:4:"tijd";s:9:"0 minuten";s:14:"soort werkvorm";s:8:"onbekend";}i:2;a:1:{s:14:"intelligenties";a:0:{}}i:3;a:1:{s:6:"inhoud";s:0:"";}}...Terugblik: a:4:{i:0;a:2:{s:8:"werkvorm";s:8:"onbekend";s:15:"organisatievorm";s:3:"nvt";}i:1;a:2:{s:4:"tijd";s:9:"0 minuten";s:14:"soort werkvorm";s:8:"onbekend";}i:2;a:1:{s:14:"intelligenties";a:0:{}}i:3;a:1:{s:6:"inhoud";s:0:"";}}section2:Kern...section3:Thema 1: Zelfstandig eclipse installeren...Ervaren: a:4:{i:0;a:2:{s:8:"werkvorm";s:29:"verhalen vertellen bij foto\'s";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"5 minuten";s:14:"soort werkvorm";s:9:"ijsbreker";}i:2;a:1:{s:14:"intelligenties";a:4:{i:0;s:2:"VL";i:1;s:2:"VR";i:2;s:1:"N";i:3;s:2:"IR";}}i:3;a:1:{s:6:"inhoud";a:2:{i:0;s:53:"Tonen afbeeldingen van werkomgevingen: wie herkent de";i:1;s:17:"				werkomgeving?";}}}...Reflecteren: a:4:{i:0;a:2:{s:8:"werkvorm";s:12:"brainstormen";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"5 minuten";s:14:"soort werkvorm";s:9:"discussie";}i:2;a:1:{s:14:"intelligenties";a:3:{i:0;s:2:"VL";i:1;s:2:"IR";i:2;s:2:"IA";}}i:3;a:1:{s:6:"inhoud";a:8:{i:0;s:57:"5 minuten brainstormen om te reflecteren op de voorgaande";i:1;s:17:"				afbeeldingen.";i:2;s:70:"				De uiteindelijke vraag om te beantwoorden: \'Hoe zou een werkplaats";i:3;s:37:"				voor een programmeur eruit zien?\'";i:4;s:41:"				Wat valt er op aan deze werkplaatsen?";i:5;s:73:"				Link leggen naar een programmeeromgeving: niet fysiek, maar virtueel.";i:6;s:65:"				Wie kan bedenken wat voor gereedschap erbij programmeren komt";i:7;s:11:"				kijken?";}}}...Conceptualiseren: a:4:{i:0;a:2:{s:8:"werkvorm";s:11:"presentatie";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"5 minuten";s:14:"soort werkvorm";s:18:"docent gecentreerd";}i:2;a:1:{s:14:"intelligenties";a:3:{i:0;s:2:"VL";i:1;s:2:"VR";i:2;s:2:"IR";}}i:3;a:1:{s:6:"inhoud";a:5:{i:0;s:33:"Kort uitleggen wat IDE/eclipse is";i:1;s:54:"				(programmeeromgeving)/waarvoor het wordt gebruikt.";i:2;s:31:"				Korte demo ter kennismaking";i:3;s:51:"				Wat zijn de randvoorwaarden van de installatie?";i:4;s:51:"				!! Laatste sheet met randvoorwaarden open laten";}}}...Toepassen: a:4:{i:0;a:2:{s:8:"werkvorm";s:19:"verwerkingsopdracht";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:10:"15 minuten";s:14:"soort werkvorm";s:24:"individuele werkopdracht";}i:2;a:1:{s:14:"intelligenties";a:2:{i:0;s:2:"VL";i:1;s:2:"IA";}}i:3;a:1:{s:6:"inhoud";a:4:{i:0;s:32:"Student installeert zelf eclipse";i:1;s:81:"				Aanvullende opdracht (capaciteit): importeren voorbeeldproject van blackboard";i:2;s:33:"				of een nieuw project aanmaken";i:3;s:54:"				Na 10min controleren of dit bij iedereen is gelukt";}}}...section3:Thema 2: Java-code lezen en uitleggen wat er gebeurt...Ervaren: a:4:{i:0;a:2:{s:8:"werkvorm";s:8:"metafoor";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"5 minuten";s:14:"soort werkvorm";s:9:"ijsbreker";}i:2;a:1:{s:14:"intelligenties";a:2:{i:0;s:2:"VL";i:1;s:2:"IR";}}i:3;a:1:{s:6:"inhoud";a:2:{i:0;s:62:"Achterhalen wie wel eens adhv van een recept/handleiding heeft";i:1;s:12:"				gewerkt.";}}}...Reflecteren: a:4:{i:0;a:2:{s:8:"werkvorm";s:12:"brainstormen";s:15:"organisatievorm";s:10:"groepswerk";}i:1;a:2:{s:4:"tijd";s:9:"5 minuten";s:14:"soort werkvorm";s:9:"discussie";}i:2;a:1:{s:14:"intelligenties";a:4:{i:0;s:2:"VL";i:1;s:2:"LM";i:2;s:2:"IR";i:3;s:2:"IA";}}i:3;a:1:{s:6:"inhoud";a:4:{i:0;s:60:"Studenten met buurman/vrouw overleggen hoeveel verschillende";i:1;s:70:"				stappen er zijn bij het uitvoeren van een handleiding. Tijdens het";i:2;s:71:"				uitvoeren van taken voeren wij onbewust veel contextgevoelige taken";i:3;s:35:"				uit een computer kent dit niet.";}}}...Conceptualiseren: a:4:{i:0;a:2:{s:8:"werkvorm";s:12:"demonstratie";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:10:"10 minuten";s:14:"soort werkvorm";s:18:"docent gecentreerd";}i:2;a:1:{s:14:"intelligenties";a:3:{i:0;s:2:"VL";i:1;s:2:"VR";i:2;s:2:"IR";}}i:3;a:1:{s:6:"inhoud";a:2:{i:0;s:61:"Tonen pseudo-code bij vorig recept of handleiding (bijv. IKEA";i:1;s:16:"				handleiding)";}}}...Toepassen: a:4:{i:0;a:2:{s:8:"werkvorm";s:19:"verwerkingsopdracht";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:10:"30 minuten";s:14:"soort werkvorm";s:24:"individuele werkopdracht";}i:2;a:1:{s:14:"intelligenties";a:2:{i:0;s:2:"VL";i:1;s:2:"IA";}}i:3;a:1:{s:6:"inhoud";a:4:{i:0;s:34:"SIMPEL: uitleggen wat de code doet";i:1;s:32:"				BASIS: schrijven pseudo-code";i:2;s:71:"				COMPLEX: zelf code schrijven, als voorschot op volgende week (extra";i:3;s:15:"				uitdaging).";}}}section2:Afsluiting...Huiswerk: a:4:{i:0;a:2:{s:8:"werkvorm";s:11:"presentatie";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"2 minuten";s:14:"soort werkvorm";s:18:"docent gecentreerd";}i:2;a:1:{s:14:"intelligenties";a:2:{i:0;s:2:"VL";i:1;s:2:"IR";}}i:3;a:1:{s:6:"inhoud";a:3:{i:0;s:38:"Challenge voor eerstvolgende les maken";i:1;s:39:"				Practicum opdrachten thuis afronden";i:2;s:37:"				Huiswerk maken als extra oefening";}}}...Evaluatie: a:4:{i:0;a:2:{s:8:"werkvorm";s:12:"nabespreking";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"3 minuten";s:14:"soort werkvorm";s:18:"docent gecentreerd";}i:2;a:1:{s:14:"intelligenties";a:2:{i:0;s:2:"VL";i:1;s:2:"IR";}}i:3;a:1:{s:6:"inhoud";a:1:{i:0;s:30:"Verzamelen feedback papiertjes";}}}...Pakkend slot: a:4:{i:0;a:2:{s:8:"werkvorm";s:11:"presentatie";s:15:"organisatievorm";s:7:"plenair";}i:1;a:2:{s:4:"tijd";s:9:"1 minuten";s:14:"soort werkvorm";s:18:"docent gecentreerd";}i:2;a:1:{s:14:"intelligenties";a:3:{i:0;s:2:"VL";i:1;s:2:"VR";i:2;s:2:"IR";}}i:3;a:1:{s:6:"inhoud";a:2:{i:0;s:58:"Foto; gerelateerd aan keuzes/condities. Misschien foto van";i:1;s:27:"				blauwe/rode pil Matrix.";}}}</body></html>', $html);
    }
}
