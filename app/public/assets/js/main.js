let score_player1 = 0
let score_player2 = 0


const form = document.getElementById("createGameButton")
form.onclick = () => { createGame() }

    function createGame() {
    const input = document.getElementById("createGameForm").elements;
    let data = {
        event: "createGame",
        data: {
            "name_player1": input[0].value,
            "name_player2": input[1].value,
        }
    }
    conn.send(JSON.stringify(data));
    }

function createGameShow(message) {
    if (message.isFromClient === true) {
        let containerGameUser = document.getElementById("gameUser")
        containerGameUser.innerHTML = "<p>" + message.data.name_player1 + " : <button id='positive_score1' type='button'>+ </button><span id='score_player1'>" + message.data.score_player1 +
            "</span><button id='negatif_score1' type='button' >- </button> </p>" +
            message.data.name_player2 + " : <button id='positive_score2' type='button'>+ </button><span id='score_player2'>" + message.data.score_player2 + "</span><button id='negatif_score2'" +
            " type='button'> - </button><br> <button id='delete' type='button'>delete</button>  <button id='endGame' type='button'>Fin de partie</button></p>"
        const positive_score1 = document.getElementById("positive_score1")
        const positive_score2 = document.getElementById("positive_score2")
        const negatif_score1 = document.getElementById("negatif_score1")
        const negatif_score2 = document.getElementById("negatif_score2")
        const delete_game = document.getElementById("delete")
        const end_game = document.getElementById("endGame")

        end_game.onclick = () => {
            containerGameUser.innerHTML = ""
            endGame(message)
        }

        delete_game.onclick = () => {
            containerGameUser.innerHTML = ""
            deleteGame(message)
        }
        positive_score1.onclick = () => {
            updateScorePlayer(positive_score1, message)
        }
        positive_score2.onclick = () => {
            updateScorePlayer(positive_score2, message)
        }
        negatif_score1.onclick = () => {
            updateScorePlayer(negatif_score1, message)
        }
        negatif_score2.onclick = () => {
            updateScorePlayer(negatif_score2, message)
        }
    } else {
        let listGameUser = document.getElementById("babyfoot-list")
        let lastGameList = listGameUser.lastElementChild
        let firstGameList = listGameUser.firstElementChild
        firstGameList.insertAdjacentHTML('beforebegin', "<article id='"+ message.data.idGame +"'> <p>" + message.data.name_player1 + " : <span class='score_player2'> " + message.data.score_player1 + "</span> VS "
            + message.data.name_player2 + " : <span class='score_player2'>" + message.data.score_player2 + " </span> || " + message.data.date + "</p> </article>")
        lastGameList.remove()
    }
}


function updateScorePlayer(e, message) {

    let score = 0
    let player = 1
    switch (e.id) {
        case "positive_score1":
            ++score_player1
            score = score_player1
            player = 1
            break
        case "positive_score2":
            ++score_player2
            score = score_player2
            player = 2
            break
        case "negatif_score1":
            --score_player1
            score = score_player1
            player = 1
            break
        case "negatif_score2":
            --score_player2
            score = score_player2
            player = 2
            break
    }
    document.getElementById('score_player' + player).innerHTML = score;
    let data = {
        event: "updateGame",
        data: {
            "identified_player": player,
            "score": score,
            "idGame": message.data.idGame,
            "status": message.data.status
        }
    }
    conn.send(JSON.stringify(data));
}

function deleteGame(message)
{
    let data = {
        event: "deleteGame",
        data: {
            "idGame": message.data.idGame,
        }
    }
    conn.send(JSON.stringify(data))
}

function deleteGameList(message)
{
    let containerGameUser = document.getElementById(message.idGame)
    containerGameUser.innerHTML = ""
}

function updateGameList(message)
{
    let score_update
    message.data.player === "score_player1" ? score_update = 0 : score_update = 1
    let updatedGameList = document.getElementById(message.idGame)
    let spans = updatedGameList.getElementsByTagName("span")
    spans[score_update].innerHTML = message.data.score
}

function endGame(message)
{
    let data = {
        event: "endGame",
        data: {
            "idGame": message.data.idGame,
        }
    }
    conn.send(JSON.stringify(data))
}