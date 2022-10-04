<?php

namespace App\Service;

use App\Controller\BabyFootManager;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Router implements MessageComponentInterface
{
    public $babyFootManager;
    protected $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->babyFootManager = new BabyFootManager();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ($conn->resourceId)\n";

    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $user_id = $from->resourceId;
        $data = json_decode($msg, false);
        $response = match ($data->event) {
            "createGame" => $this->babyFootManager->createGame($data->data, $user_id),
            "updateGame" => $this->babyFootManager->updateGame($data->data, $user_id),
            "deleteGame" => $this->babyFootManager->deleteGame($data->data, $user_id),
            "endGame" => $this->babyFootManager->endGame($data->data, $user_id),
            default => "test"
        };

        foreach ($this->clients as $client) {

            if ($from !== $client && $response->otherClient === true) {
                // The sender is not the receiver, send to each client connected
                $response->isFromClient = false;
                $response_encoded = json_encode($response);
                $client->send($response_encoded);
            }
            if ($from == $client && $response->client) {
                $response->isFromClient = true;
                $response_encoded = json_encode($response);
                $client->send($response_encoded);
            }
        }
    }


    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }


}