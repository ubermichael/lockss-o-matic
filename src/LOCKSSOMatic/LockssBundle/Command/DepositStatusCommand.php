<?php

namespace LOCKSSOMatic\LockssBundle\Command;

use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use LOCKSSOMatic\CrudBundle\Entity\Box;
use LOCKSSOMatic\CrudBundle\Entity\Content;
use LOCKSSOMatic\CrudBundle\Entity\Deposit;
use LOCKSSOMatic\CrudBundle\Entity\DepositStatus;
use LOCKSSOMatic\CrudBundle\Entity\Pln;
use LOCKSSOMatic\CrudBundle\Service\AuIdGenerator;
use Monolog\Logger;
use SoapClient;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DepositStatusCommand extends ContainerAwareCommand
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Box[]
     */
    private $boxes;

    /**
     * @var int
     */
    private $boxCount;

    /**
     * @var AuIdGenerator
     */
    private $idGenerator;

    public function configure()
    {
        $this->setName('lom:deposit:status');
        $this->setDescription('Check that the deposits in LOCKSS have the same checksum.');
        $this->addArgument(
            'plns',
            InputArgument::IS_ARRAY,
            'Database IDs of the PLNs to check.'
        );
        $this->addOption(
            'all',
            '-a',
            InputOption::VALUE_NONE,
            'Process all deposits.'
        );
        $this->addOption(
            'dry-run',
            '-d',
            InputOption::VALUE_NONE,
            'Export only, do not update any internal configs.'
        );
        $this->addOption(
            'limit',
            '-l',
            InputOption::VALUE_OPTIONAL,
            'Limit the number of deposits checked.'
        );
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->logger = $container->get('logger');
        $this->em = $container->get('doctrine')->getManager();
        $this->idGenerator = $this->getContainer()->get('crud.au.idgenerator');
    }

    protected function loadBoxes(Pln $pln)
    {
        $boxes = $pln->getBoxes();
        $this->boxes = array();
        $this->boxCount = count($boxes);
        foreach ($boxes as $box) {
            try {
                $statusClient = new SoapClient(
                    "http://{$box->getIpAddress()}:{$box->getWebServicePort()}/ws/DaemonStatusService?wsdl",
                    array(
                    'soap_version' => SOAP_1_1,
                    'login'        => $pln->getUsername(),
                    'password'     => $pln->getPassword(),
                    'trace'        => false,
                    'exceptions'   => true,
                    'cache'        => WSDL_CACHE_NONE,
                    )
                );
                $readyResponse = $statusClient->isDaemonReady();
                if ($readyResponse) {
                    $this->boxes[] = $box;
                } else {
                    $this->logger->error("Box {$box->getId()} is not ready.");
                }
            } catch (Exception $e) {
                $this->logger->error($box->getHostname() . '/' . $box->getIpAddress() . ' - ' . $e->getMessage());
                continue;
            }
        }
    }

    protected function checkContent(Box $box, Content $content)
    {
        $auid = $this->idGenerator->fromContent($content);
        $checksumType = $content->getChecksumType();

        $url = "http://{$box->getIpAddress()}:{$box->getWebServicePort()}/ws/HasherService?wsdl";
        $this->logger->notice("Checking content {$content->getId()} on box {$box->getId()}");
        $hasherClient = new SoapClient(
            $url,
            array(
            'soap_version' => SOAP_1_1,
            'login'        => $box->getPln()->getUsername(),
            'password'     => $box->getPln()->getPassword(),
            'exceptions'   => true,
            'cache'        => WSDL_CACHE_NONE,
            )
        );
        $hashResponse = $hasherClient->hash(array(
            'hasherParams' => array(
                'recordFilterStream' => true,
                'hashType'           => 'V3File',
                'algorithm'          => $checksumType,
                'url'                => $content->getUrl(),
                'auId'               => $auid,
        )));
        if (property_exists($hashResponse->return, 'blockFileDataHandler')) {
            $matches = array();
            if (preg_match(
                "/^([a-fA-F0-9]+)\s+http/m",
                $hashResponse->return->blockFileDataHandler,
                $matches
            )) {
                $checksumValue = $matches[1];
                return strtoupper($checksumValue);
            } else {
                return '-';
            }
        } else {
            return $hashResponse->return->errorMessage;
        }
    }

    protected function checkDeposit(Deposit $deposit)
    {
        $matches = 0;
        $status = array();
        $pln = $deposit->getPln();
        $this->logger->notice("Checking deposit {$deposit->getId()}");
        foreach ($pln->getBoxes() as $box) {
            $status[$box->getId()] = array();
            foreach ($deposit->getContent() as $content) {
                try {
                    $checksum = $this->checkContent($box, $content);
                    $status[$box->getId()][$content->getId()] = $checksum;
                } catch (Exception $e) {
                    $status[$box->getId()][$content->getId()] = '*';
                }
                if ($checksum === $content->getChecksumValue()) {
                    $matches++;
                }
            }
        }
        $agreement = $matches / (count($deposit->getContent()) * count($pln->getBoxes()));
        $depositStatus = new DepositStatus();
        $depositStatus->setDeposit($deposit);
        $depositStatus->setQueryDate(new DateTime());
        $depositStatus->setAgreement($agreement);
        $depositStatus->setStatus($status);
        $this->logger->info("Deposit {$deposit->getId()}: " . sprintf("%3.2f%%", ($agreement * 100)));
        return $depositStatus;
    }

    /**
     * @return Pln
     * @param array|null $plnIds
     */
    protected function getPlns($plnIds = null)
    {
        if ($plnIds === null || !is_array($plnIds) || count($plnIds) === 0) {
            return $this->em->getRepository('LOCKSSOMaticCrudBundle:Pln')->findAll();
        }
        return $this->em->getRepository('LOCKSSOMaticCrudBundle:Pln')->findBy(
            array('id' => $plnIds)
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $allDeposits = $input->getOption('all');
        $dryRun = $input->getOption('dry-run');
        $limit = $input->getOption('limit');
        $depositRepository = $this->em->getRepository('LOCKSSOMaticCrudBundle:Deposit');
        $count = 0;

        if ($input->getOption('all')) {
            $this->logger->notice("Getting all deposits.");
            $deposits = $depositRepository->findAll();
        } else {
            $this->logger->notice("Getting unagreed deposits.");
            $deposits = $depositRepository->createQueryBuilder('d')
                ->where('d.agreement <> 1')
                ->orWhere('d.agreement is null')
                ->getQuery()
                ->getResult();
        }

        $this->logger->notice("Found " . count($deposits) . " deposits to check.");
        foreach ($deposits as $deposit) {
            if ($deposit->getAgreement() == 1 && (!$allDeposits)) {
                continue;
            }
            $status = $this->checkDeposit($deposit);
            $deposit->setAgreement($status->getAgreement());
            if ($dryRun) {
                continue;
            }
            $this->em->persist($status);
            $this->em->flush();
            $count++;
            if ($limit && $count >= $limit) {
                break;
            }
        }
    }
}