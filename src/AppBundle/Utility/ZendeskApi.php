<?php

namespace AppBundle\Utility;

class ZendeskApi {

  public function __construct($user, $pass) {
    $this->user = $user;
    $this->pass = $pass;
  }

  public function zendeskApiCall($action, $url, $data = [])
  {
      $json = json_encode($data);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://dattoinc.zendesk.com' . $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($ch, CURLOPT_USERPWD, $this->user . ':' . $this->pass);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);
      if (in_array($action, ['POST', 'PUT', 'DELETE'])) {
          curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
      }

      $body = curl_exec($ch);
      $info = curl_getinfo($ch);
      $error = curl_error($ch);

      $response = json_decode($body, true);

      $debugText = date('c') . ' - ' . $info['http_code'] . ' - ' . $action . ' - ' . $info['url'];

      if (is_null($response) || json_last_error() !== JSON_ERROR_NONE) {
          throw new Exception('Malformed JSON Response (' . $debugText . '): ' . $body);
      } elseif ($error !== '') {
          throw new Exception('Curl failed (' . $debugText . '): ' . $error);
      } elseif (isset($response['error'])) {
          throw new Exception('API call failed (' . $debugText . '): ' . $body, $info['http_code']);
      }

      return $response;
  }
}
