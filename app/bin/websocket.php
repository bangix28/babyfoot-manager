<?php

use App\Service\MyChat;
use Ratchet\App;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new App('localhost', 9001, '0.0.0.0');
$app->route('/', new MyChat, array('*'));
$app->run();

?>