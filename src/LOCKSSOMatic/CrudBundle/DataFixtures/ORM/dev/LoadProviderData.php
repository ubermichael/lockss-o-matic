<?php

/* 
 * The MIT License
 *
 * Copyright 2014. Michael Joyce <ubermichael@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace LOCKSSOMatic\CrudBundle\DataFixtures\ORM\dev;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LOCKSSOMatic\CoreBundle\Utilities\AbstractDataFixture;
use LOCKSSOMatic\CrudBundle\Entity\ContentProvider;

/**
 * Load some test content provider data into the database for development.
 */
class LoadProviderData extends AbstractDataFixture implements OrderedFixtureInterface
{

    /**
     * PLNs must be loaded before Boxes. This sets the order.
     *
     * @return int the order
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * Load the PLNs.
     *
     * @param ObjectManager $manager
     */
    public function doLoad(ObjectManager $manager)
    {
        $provider = new ContentProvider();
        $provider->setUuid('473a1b0d-425f-417b-94cf-28c3fc04b0e2');
        $provider->setPermissionurl("http://example.com/path/to/permissions");
        $provider->setName("Test provider");
        $provider->setMaxFileSize("10000");
        $provider->setMaxAuSize("1000000");
        $provider->setContentOwner($this->getReference("owner"));
        $provider->setPln($this->getReference("pln-franklin"));
        $provider->setPlugin($this->getReference('plugin'));
        $manager->persist($provider);
        $manager->flush();
    }
    
    /**
     * {@inheritDocs}
     */
    protected function getEnvironments()
    {
        return array('dev');
    }
}
