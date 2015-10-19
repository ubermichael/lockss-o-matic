<?php

namespace LOCKSSOMatic\ImportExportBundle\Tests\Command;

use LOCKSSOMatic\CoreBundle\Utilities\AbstractTestCase;
use LOCKSSOMatic\ImportExportBundle\Command\PLNPluginImportCommand;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-09-21 at 09:13:38.
 */
class PLNPluginImportCommandTest extends AbstractTestCase
{

    /**
     * @var PLNPluginImportCommand
     */
    protected $command;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->command = new PLNPluginImportCommand;
    }

    public function testExecute() {
        $file = __DIR__ . '/../data/SFUCartoonsPlugin.jar';
        $repo = $this->em->getRepository('LOCKSSOMaticCrudBundle:Plugin');

        $plugins = $repo->findAll();
        $count = count($plugins);

        $null = $repo->findOneBy(array(
            'name' => 'Simon Fraser University Library Editorial Cartoons Collection Plugin'
        ));
        $this->assertNull($null);

        $this->runCommand('lom:import:plnplugin', array(
            '--nocopy' => true,
            'plugin_files' => array($file),
        ));

        $plugin = $repo->findOneBy(array(
            'name' => 'Simon Fraser University Library Editorial Cartoons Collection Plugin'
        ));
        $this->assertNotNull($plugin);
        $this->assertInstanceOf('LOCKSSOMatic\CrudBundle\Entity\Plugin', $plugin);
        $this->assertEquals(
            'ca.sfu.lib.plugin.cartoons.SFUCartoonsPlugin',
            $plugin->getPluginIdentifier()
        );
    }

}
