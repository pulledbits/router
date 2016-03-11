<?php
namespace Teach\Domain;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-14 at 13:44:20.
 */
class LesplanTest extends \Teach\DomainTest
{

    public function testInteract()
    {
        $factory = new Factory(self::$pdo);
        $object = $factory->createLesplan('1');
        $lesplanlayout = $object->interact(new \Teach\Interactions\Web\Lesplan\Factory());
        $this->assertInstanceOf('\Teach\Interactions\Web\Document', $lesplanlayout);
    }
}
