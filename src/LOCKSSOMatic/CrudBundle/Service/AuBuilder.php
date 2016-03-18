<?php

namespace LOCKSSOMatic\CrudBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use LOCKSSOMatic\CrudBundle\Entity\Au;
use LOCKSSOMatic\CrudBundle\Entity\AuProperty;
use LOCKSSOMatic\CrudBundle\Entity\Content;
use Monolog\Logger;
use Symfony\Component\Routing\Router;

class AuBuilder
{

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ObjectManager
     */
    private $em;
    
    /**
     * @var AuPropertyGenerator
     */
    private $generator;
    
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    public function setRegistry(Registry $registry)
    {
        $this->em = $registry->getManager();
    }
    
    public function setPropertyGenerator(AuPropertyGenerator $generator) {
        $this->generator = $generator;
    }

    /**
     * Build an AU for the content item.
     *
     * @return Au
     * @param Content $content
     */
    public function fromContent(Content $content)
    {
        $au = new Au();
        $au->setAuid($content->generateAuid());
        $au->addContent($content);        
        $provider = $content->getDeposit()->getContentProvider();

        $au->setContentprovider($provider);
        $au->setPln($provider->getPln());
        $au->setPlugin($provider->getPlugin());
        
        $this->em->persist($au);
        $this->em->flush();
        
        $this->generator->generateProperties($au);
        
        return $au;
    }
}