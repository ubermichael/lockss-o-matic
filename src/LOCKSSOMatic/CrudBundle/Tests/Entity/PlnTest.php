<?php

namespace LOCKSSOMatic\CrudBundle\Tests\Entity;

use LOCKSSOMatic\CoreBundle\Utilities\AbstractTestCase;
use LOCKSSOMatic\CrudBundle\Entity\Pln;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-09-15 at 12:04:12.
 */
class PlnTest extends AbstractTestCase
{

    /**
     * @var Pln
     */
    protected $pln;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->pln = $this->references->getReference('pln-franklin');
    }

    public function testDefaults() {
        $this->assertEquals('franklin', $this->pln->getName());
    }

    public function testGetProperties() {
        $props = $this->pln->getPlnProperties();
        $this->assertEquals(5, count($props));
    }

    public function testGetProperty1() {
        $prop = $this->pln->getProperty('leaf');
        $this->assertNotNull($prop);
        $this->assertInstanceOf('LOCKSSOMatic\CrudBundle\Entity\PlnProperty', $prop);
    }

    public function testGetProperty2() {
        $prop = $this->pln->getProperty('autumn');
        $this->assertNotNull($prop);
        $this->assertInstanceOf('LOCKSSOMatic\CrudBundle\Entity\PlnProperty', $prop);
        $this->assertEquals('lots of leaves', $prop->getPropertyValue());
    }

    public function testGetRootPluginProperties() {
        $props = $this->pln->getRootPluginProperties();
        $this->assertEquals(2, count($props));
    }

    public function testAlterProperty() {
        $prop = $this->pln->getProperty('winter');
        $prop->setPropertyValue('very snowy.');
        $this->em->flush();
        $altered = $this->pln->getProperty('winter');
        $this->assertEquals('very snowy.', $altered->getPropertyValue());
    }
}