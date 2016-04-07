<?php
namespace Teach\Domain;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-14 at 13:44:20.
 */
class FactoryTest extends \Teach\DomainTest
{

    public function testCreateLesplan()
    {
        $object = new Factory(new \Teach\Interactions\Database(self::$pdo));
        $this->assertInstanceOf('\Teach\Domain\Lesplan', $object->createLesplan('1'));
    }
}
