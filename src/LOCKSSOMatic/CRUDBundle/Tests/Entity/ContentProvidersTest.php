<?php

/*
 * The MIT License
 *
 * Copyright (c) 2014 Mark Jordan, mjordan@sfu.ca.
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

namespace LOCKSSOMatic\CRUDBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use J20\Uuid\Uuid;
use LOCKSSOMatic\CRUDBundle\Entity\Aus;
use LOCKSSOMatic\CRUDBundle\Entity\ContentProviders;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-10-14 at 10:48:17.
 */
class ContentProvidersTest extends KernelTestCase
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    public function testGetPermissionHost()
    {
        $contentProvider = new ContentProviders();
        $contentProvider->setPermissionUrl('http://www.example.com/path/to/statment.html');
        $this->assertEquals('www.example.com', $contentProvider->getPermissionHost());
    }

    public function testGenerateUuid()
    {
        $contentProvider = new ContentProviders();
        $this->em->persist($contentProvider);
        $this->assertRegExp('/^.{36}$/', $contentProvider->getUuid());
        $this->em->remove($contentProvider);
        
        $id = Uuid::v4();
        $contentProvider = new ContentProviders();
        $contentProvider->setUuid($id);
        $this->em->persist($contentProvider);
        $this->assertEquals($id, $contentProvider->getUuid());
        $this->em->remove($contentProvider);        
    }
    
}
