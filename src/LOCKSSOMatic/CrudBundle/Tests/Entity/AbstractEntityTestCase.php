<?php

namespace LOCKSSOMatic\CrudBundle\Tests\Entity;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseTestCase;

class AbstractEntityTestCase extends BaseTestCase {

    protected $em;

    protected $references;

    protected function setUp() {
        $fixtures = array(
            'LOCKSSOMatic\CrudBundle\DataFixtures\ORM\LoadPlnTestData',
            'LOCKSSOMatic\CrudBundle\DataFixtures\ORM\LoadPluginTestData',
            'LOCKSSOMatic\CrudBundle\DataFixtures\ORM\LoadPluginPropertyTestData',
            'LOCKSSOMatic\CrudBundle\DataFixtures\ORM\LoadOwnerTestData',
            'LOCKSSOMatic\CrudBundle\DataFixtures\ORM\LoadProviderTestData',
            'LOCKSSOMatic\CrudBundle\DataFixtures\ORM\LoadDepositTestData',
            'LOCKSSOMatic\CrudBundle\DataFixtures\ORM\LoadAuTestData',
            'LOCKSSOMatic\CrudBundle\DataFixtures\ORM\LoadAuPropertyTestData',
        );
        $this->references = $this->loadFixtures($fixtures)->getReferenceRepository();
        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

}