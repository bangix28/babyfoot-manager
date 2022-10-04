<?php

use App\Service\Router;
use Ratchet\App;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new App('localhost', 9001, '0.0.0.0');
$app->route('/', new Router, array('*'));
$app->run();

?>