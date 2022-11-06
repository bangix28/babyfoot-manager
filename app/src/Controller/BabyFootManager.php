<?php

namespace App\Controller;


use DateTime;
use PDO;

class BabyFootManager
{

    protected $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=mysql;dbname=babyfoot_manager',
            'root',
            'root'

        );
    }


    /**
     * @param $data
     * @param $userId
     * @return object
     * Créer une partie au statut "en cours" et retourner l'information qu'une partie a été créée aux autres clients connectés.
     */
    public function createGame($data, $userId)
    {
        $status = "live";
        $query = "INSERT INTO `babyfoot_manager` (`name_player1`, `name_player2`, `score_player1`, `score_player2`,user_id, status,`date`) VALUES (?,?,?,?,?,?,now());";
        $prepared_statements = $this->pdo->prepare($query);
        $prepared_statements->execute(array($data->name_player1, $data->name_player2, 0, 0, $userId, $status));
        $date = new DateTime('now');
        $date_formated = $date->format('Y-m-d H:i:s');

        return (object)array(
            "event" => "createGame",
            "isFromClient" => false,
            "data" => array(
                "idGame" => $this->pdo->lastInsertId(),
                "name_player1" => $data->name_player1,
                "name_player2" => $data->name_player2,
                "score_player1" => 0,
                "score_player2" => 0,
                "status" => $status,
                "date" => $date_formated

            ),
            "client" => true,
            "otherClient" => true,
        );

    }

    /**
     * @param $data
     * @param $userId
     * @return object|void
     * Met à jour une partie s'il s'agit du même utilisateur qui a créé la partie.
     */
    public function updateGame($data, $userId)
    {
        if ($this->verifyUserId($data, $userId)) {
            $playerUpdate = ($data->identified_player === 1) ? "score_player1" : "score_player2";
            $query = "UPDATE babyfoot_manager SET $playerUpdate = ? WHERE id = ?  ";
            $prepared_statements = $this->pdo->prepare($query);
            $prepared_statements->execute(array($data->score, $data->idGame));

            return (object)array(
                "event" => "updateGame",
                "idGame" => $data->idGame,
                "data" => array(
                    "score" => $data->score,
                    "player" => $playerUpdate,
                    "status" => $data->status
                ),
                "client" => false,
                "otherClient" => true,
            );
        }
    }

    /**
     * @param $data
     * @param $userId
     * @return bool
     * Récupère l'ID utilisateur d'une partie et le compare avec l'ID d'ouverture de session assigné par le serveur.
     */
    public function verifyUserId($data, $userId)
    {
        $query_userId = "SELECT user_id FROM babyfoot_manager WHERE id = ?";
        $prepared_statements_userId = $this->pdo->prepare($query_userId);
        $prepared_statements_userId->execute(array($data->idGame));
        $fetch_query = $prepared_statements_userId->fetch(PDO::FETCH_OBJ);

        return $fetch_query->user_id == $userId;
    }

    /**
     * @param $data
     * @param $userId
     * @return object|void
     * Supprime une partie s'il s'agit du même utilisateur qui a créé la partie.
     */
    public function deleteGame($data, $userId)
    {
        if ($this->verifyUserId($data, $userId)) {
            $query_delete = "DELETE FROM babyfoot_manager WHERE id = ?";
            $prepared_statements = $this->pdo->prepare($query_delete);
            $prepared_statements->execute(array($data->idGame));

            return (object)array(
                "event" => "deleteGame",
                "idGame" => $data->idGame,
                "data" => array(),
                "client" => false,
                "otherClient" => true,
            );
        }
    }

    /**
     * @return array|false
     * Envoie les 10 dernières parties créées
     */
    public function showGames()
    {
        $query = "SELECT * FROM babyfoot_manager ORDER BY date DESC LIMIT 10";
        $prepared_statements = $this->pdo->prepare($query);
        $prepared_statements->execute();

        return $prepared_statements->fetchAll(PDO::FETCH_OBJ);

    }

    /**
     * @param $data
     * @param $userId
     * @return object|void
     * Met à jour le status "in progress" à "ended" d'une partie s'il s'agit du même utilisateur qui a créé la partie
     */
    public function endGame($data, $userId)
    {
        if ($this->verifyUserId($data, $userId)) {
            $status = "ended";
            $query = "UPDATE babyfoot_manager SET status = ? WHERE id = ?  ";
            $prepared_statements = $this->pdo->prepare($query);
            $prepared_statements->execute(array($status, $data->idGame));

            return (object)array(
                "event" => "updateGame",
                "idGame" => $data->idGame,
                "data" => array(
                    "status" => $status
                ),
                "client" => false,
                "otherClient" => false,
            );
        }
    }


}