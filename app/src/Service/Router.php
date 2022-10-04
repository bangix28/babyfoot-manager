<?php

namespace App\Service;

use App\Controller\BabyFootManager;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

/**
 *
 */
class Router implements MessageComponentInterface
{
    public $babyFootManager;
    protected $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->babyFootManager = new BabyFootManager();
    }

    /**
     * @param ConnectionInterface $conn
     * @return void
     * Stockez la nouvelle connexion pour envoyer des messages plus tard
     */
    public function onOpen(ConnectionInterface $conn)
    {

        $this->clients->attach($conn);
        echo "New connection! ($conn->resourceId)\n";

    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     * @return void
     * Récupère les messages du client et appelle la fonction requise pour traiter le message et envoie à tous les clients les informations qui ont été traitées.
     */
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
                // Envoie un message à tous les utilisateurs qui ne sont pas l'expéditeur.
                $response->isFromClient = false;
                $response_encoded = json_encode($response);
                $client->send($response_encoded);
            }
            if ($from == $client && $response->client) {
                //Envoie une réponse à l'expéditeur
                $response->isFromClient = true;
                $response_encoded = json_encode($response);
                $client->send($response_encoded);
            }
        }
    }


    /**
     * @param ConnectionInterface $conn
     * @return void
     * Quand la connection au client et perdu, supprime celui-ci
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * @param ConnectionInterface $conn
     * @param Exception $e
     * @return void
     * Quand une erreur est envoyer arrete la connection
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }


}