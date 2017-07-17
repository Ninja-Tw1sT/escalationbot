<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use AppBundle\Utility\ZendeskApi;

class EscalationCommand extends ContainerAwareCommand {
  protected function configure() {
    $this->setName('app:example');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $container = $this->getContainer();
    $zendesk = $container->get('api.zendesk');
    //$zendesk = $this->getApplication()->getKernel()->getContainer()->get('api.zendesk');
    $viewID = 56695823;

    $ticketData = $zendesk->zendeskApiCall('GET', "/api/v2/views/".$viewID."/execute.json");
    $ticketDataRows = $ticketData["rows"][0];

    $output->writeln($ticketDataRows['ticket_id']);
  }

}
