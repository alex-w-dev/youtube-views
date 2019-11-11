<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/lib/utils.php';
require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

include_once __DIR__ . '/google/remotes.php';

$app->get('/', function (Request $request, Response $response, $args) {
  $response->getBody()->write("Hello world!");
  return $response;
});
$app->get('/fail', function (Request $request, Response $response, $args) {
  $response->getBody()->write("Hello world fail!");
  return $response;
});

$app->run();