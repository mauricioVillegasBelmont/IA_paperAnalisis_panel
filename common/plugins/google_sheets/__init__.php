<?php

require_once __dir__ . "/settings.php";

use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;


// Autenticación con cuenta de servicio
class Google_sheets{
  private $client;
  private $service;
  public function __construct() {
    $credentials = __dir__."/". SERVICE_ACCOUNT_CREDENTIALS_JSON;
    $this->client = new Google_Client();
    $this->client->setApplicationName('Europio Google Sheets Conection');
    $this->client->setScopes('https://www.googleapis.com/auth/spreadsheets');
    $this->client->setAuthConfig($credentials);
    $this->client->setAccessType('offline');
    $this->client->setPrompt('select_account consent');
    // $this->client->setAuthConfig(GCLOUD_CEDENTIALS);
    putenv('GOOGLE_APPLICATION_CREDENTIALS='. __dir__ . "/credentials.json" .'');
    $this->client->useApplicationDefaultCredentials();
    $this->service = new Google\Service\Sheets($this->client);
  
    // $token = __dir__."/". JSON_WEB_TOKEN;
    // if (file_exists($token)) {
    //   $accessToken = json_decode(file_get_contents($token), true);
    //   $this->client->setAccessToken($token);
    // }
  }
 

  public function appendValues($spreadsheetId, $range, $values = [])
  {

    // $this->client = $this->client;
    // $this->client->useApplicationDefaultCredentials();
    // $this->client->addScope('https://www.googleapis.com/auth/spreadsheets');
    // $service = new Google\Service\Sheets($this->client);
    //add the values to be appended
    //execute the request
    try {
      
      $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
      ]);
      $params = [
        'valueInputOption' => 'RAW'
      ];
      $result = $this->service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
      return $result;
      
    } catch (Exception $e) {
      // TODO(developer) - handle error appropriately
      return $e->getMessage();
    }
    // [END sheets_append_values]
  }
  function batchUpdateValues($spreadsheetId, $range, $valueInputOption)
  {
    $this->client->useApplicationDefaultCredentials();
    $this->client->addScope(Google\Service\Drive::DRIVE);
    $service = new Google_Service_Sheets($this->client);
    $values = [];
    try {

      $data[] = new Google_Service_Sheets_ValueRange([
        'range' => $range,
        'values' => $values
      ]);
      $body = new Google_Service_Sheets_BatchUpdateValuesRequest([
        'valueInputOption' => $valueInputOption,
        'data' => $data
      ]);
      $result = $this->service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
      printf("%d cells updated.", $result->getTotalUpdatedCells());
      return $result;
    } catch (Exception $e) {
      echo 'Message: ' . $e->getMessage();
    }
  }
}

function Google_sheets(){
  return new Google_sheets();
}