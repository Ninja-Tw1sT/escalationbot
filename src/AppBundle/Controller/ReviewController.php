<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Utility\ZendeskApi;

use Exception;

class ReviewController extends Controller {

  public function reviewAction() {

    $user = $this->container->getParameter('api_user');
    $pass = $this->container->getParameter('api_pass');

    $zendesk = new ZendeskApi($user, $pass);
    $results = array();
    $viewID = 56695823;

    $results = $zendesk->zendeskApiCall('GET', "/api/v2/views/".$viewID."/execute.json");

    $response = new Response(json_encode($results));
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }

  public function reviewFrontEndAction() {
    $zendesk = new ZendeskApi();
    $viewID = 56695823;

    $ticketData = $zendesk->zendeskApiCall('GET', "/api/v2/views/".$viewID."/execute.json");
    $ticketDataRows = $ticketData["rows"][0];
    //$ticketDataRows = $ticketData["rows"];

    // if($ticketDataRows === null) {
    //   $noMatches['result'] = "No tickets found";
    //   return $this->render('none.html.twig', array("noMatches" => $noMatches))
    // }

    $twigData["ticket_id"] = $ticketDataRows["ticket_id"];
    $twigData["subject"] = $ticketDataRows["subject"];

    //var_dump($ticketDataRows["ticket_id"]);
    //var_dump($ticketDataRows["subject"]);

    $userID = $ticketDataRows["assignee_id"];
    $userData = $zendesk->zendeskApiCall('GET', "/api/v2/users/".$userID.".json");
    //var_dump($userData["user"]["name"]);
    $twigData["name"] = $userData["user"]["name"];


    return $this->render('review.html.twig', array("twigData" => $twigData));
  }
}
