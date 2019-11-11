<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$referrer = 'http://localhost:3000';
$api_key = file_get_contents(getMyDir(__FILE__).'/apiKey.txt');
$client = new Google_Client();
$client->setDeveloperKey($api_key);

$headers = array('Referer' => $referrer);
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), 'headers' => $headers ));
$client->setHttpClient($guzzleClient);

$service = new Google_Service_YouTube($client);

$app->get('/api/youtube/channel-videos', function (Request $request, Response $response, $args) use ($service) {
  $queryParams = $request->getQueryParams();
  if (!isset($queryParams['channelId'])) throw new Exception('channelId is required');

  $queryParams = [
    'channelId' => $queryParams['channelId'],
    'maxResults' => 50,
    'order' => isset($queryParams['order']) ? $queryParams['order'] : 'date',
    'type' => 'video'
  ];

  $apiResponse = @$service->search->listSearch('snippet', $queryParams)->toSimpleObject()->items;
  // echo "<pre>" . print_r($apiResponse, true) . "</pre>";


  $payload = json_encode($apiResponse);

  $response->getBody()->write($payload);
  return $response
    ->withHeader('Content-Type', 'application/json');
});
