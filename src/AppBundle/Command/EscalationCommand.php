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

    // Instantiate Service Container
    $container = $this->getContainer();

    // Create ZendeskApi object
    $zendesk = $container->get('api.zendesk');

    // Get ticket information
    $viewID = 56695823;
    $ticketData = $zendesk->zendeskApiCall('GET', "/api/v2/views/".$viewID."/execute.json");
    $ticketDataRows = $ticketData["rows"][0];
    $ticketID = $ticketDataRows['ticket_id'];
    $subject = $ticketDataRows["subject"];

    // Get assignee information
    $assigneeID = $ticketDataRows["assignee_id"];
    $assigneeData = $zendesk->zendeskApiCall('GET', "/api/v2/users/".$assigneeID.".json");
    $assigneeName = $assigneeData["user"]["name"];

    // Create SlackApi object
    $slack = $container->get('api.slack');

    // Create messaging and output
    $message = $assigneeName . ' has received an escalation for https://dattoinc.zendesk.com/agent/tickets/' . $ticketID . "\nSubject: " . $subject;
    $output->writeln($message);
  }

}
