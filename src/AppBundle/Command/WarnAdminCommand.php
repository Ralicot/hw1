<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.08.2015
 * Time: 16:23
 */

namespace AppBundle\Command;

use AppBundle\Document\Document;
use AppBundle\Event\Communication\Email\EmailEvent;
use AppBundle\Service\Communication\EmailService;
use Doctrine\ODM\MongoDB\DocumentManager;
use AppBundle\Communication\Email\Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;


class WarnAdminCommand extends ContainerAwareCommand
{

    /**
     *
     * @var EmailService
     */
    private $emailService;

    /**
     *
     * @var DocumentManager
     */
    private $documentManager;


    protected function configure()
    {
        parent::configure();
        $this->setName('warn:admin');
        $this->setDescription('Warn admin about an invoice that doesn`t have it`s body');
        $this->addOption(
            'time', '-t', InputOption::VALUE_REQUIRED, 'The time between the generating the invoice and generating it`s body');


    }
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->emailService = $this->getContainer()
            ->get(EmailService::ID);
        $this->documentManager = $this->getContainer()
            ->get('doctrine_mongodb')
            ->getManager();

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time = $input->getOption('time');
        $date = (new \DateTime('now'))->modify('-'.$time.' minutes');
        echo $date->format('Y-m-d H:i:s');
        //$invoiceRep = $this->documentManager->getRepository(Document::REPOSITORY);
        //$invoicesWithProblems = $invoiceRep->createQueryBuilder()

        $qb = $this->documentManager->createQueryBuilder(Document::REPOSITORY)
            ->eagerCursor(true)
            ->field('createDate')->lte($date)
            ->field('type')->equals('invoice')
            ->field('bodyHtml')->exists(false)
            ;

        $query= $qb->getQuery();

        $invoiceWithProblems =$query->execute();
        $count=0;
        $message =  new Message();
        $message->setSubject('Invoice Warnings');
        $message->setTo('admin@site.com');
        $message->setFrom('cron_invoice_warning@site.com');
        $body ='';
        if($invoiceWithProblems) {
            var_dump('sunt probleme');
          // var_dump($invoiceWithProblems);
          //  var_dump($invoiceWithProblems[0]);
            foreach ($invoiceWithProblems as $invoice) {
                $body = $body. ' Invoice no. '. $invoice->getId() . ' has a problem'."\r\n";
                var_dump('pai');
                $count++;
                var_dump($invoice->getType());
            }
        }
        var_dump($count);
        $message->setMessage($body);
        $status = $this->emailService->send($message);
        $this->emailService->createWarningEmail($message,$status);
        return 0;
    }


}