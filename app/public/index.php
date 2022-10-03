<?php

use App\Controller\BabyFootManager;
use App\Service\MyChat;

require dirname(__DIR__) . '/vendor/autoload.php';

$babyFootManager = new BabyFootManager();
$chat = new MyChat();
$listOfGames = $babyFootManager->showGames();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form id="createGameForm">
    <label> nom joueur 1
        <input name="name_player1" type="text">
    </label>
    <label> nom joueur 2
        <input name="name_player2" type="text">
    </label>

    <button id="createGameButton" type="button" name="submit">Crée partie</button>
    <div id="gameUser">
    </div>
</form>

<article>
    <h2>Liste des partie</h2>
    <div id="babyfoot-list" class="babyfoot-list">
        <?php
        foreach ($listOfGames as $game) {

            echo <<<HTML
                <article id="$game->id">
                    <p>$game->name_player1 : <span class="score_player1"> $game->score_player1 </span> VS <span class="score_player2"> $game->name_player2 </span> : $game->score_player2 || 
                    $game->date</p>
                </article>
                HTML;
        }
        ?>
    </div>
</article>

<script src="assets/js/main.js"></script>

<script>
    let conn = new WebSocket('ws://localhost:9001');
    let token = conn.onopen
    conn.onmessage = function (e) {
        let message = JSON.parse(e.data)
        console.log(message);
        switch (message.event)
        {
            case "createGame" :
                createGameShow(message);
                break;
            case "deleteGame" :
                deleteGameList(message)
                break
            case "updateGame":
                updateGameList(message)
                break
        }
    };



</script>
</body>
</html>
