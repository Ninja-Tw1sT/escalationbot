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
    $viewID = 56695823;

    // Get ticket information
    $ticketData = $zendesk->zendeskApiCall('GET', "/api/v2/views/".$viewID."/execute.json");
    $ticketDataRows = $ticketData["rows"][0];
    $ticketID = $ticketDataRows['ticket_id'];
    $subject = $ticketDataRows["subject"];

    // Get assignee information
    $assigneeID = $ticketDataRows["assignee_id"];
    $assigneeData = $zendesk->zendeskApiCall('GET', "/api/v2/users/".$assigneeID.".json");
    $assigneeName = $assigneeData["user"]["name"];

    $message = $assigneeName . ' has received an escalation for https://dattoinc.zendesk.com/agent/tickets/' . $ticketID . "\nSubject: " . $subject;
    $output->writeln($message);
  }

}
