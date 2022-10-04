
# Babyfoot-manager

Babyfoot-manager est une application PHP et JS vanilla pour gerer une partie de babyfoot en temps réel.



## Instalation et lancement du projets

1- télécharger le repository.

2- Accédez au dossier babyfoot-manager avec votre invite de commande est créé l'image avec docker et monter cette image.
```sh
cd path/to/babyfoot-manager
docker build -t babyfoot-manager .
docker-composer up
```

4- Accédez à la page adminer http://localhost:8080 
```sh
    serveur : mysql
    Utilisateur : root
    Mot de passe :  root
```
5 - Puis importez la base de données présente dans le fichier mysql à la racine projet.


6- Installez les dépendances avec composer install dans l'invite de commande du conteneur application.
```sh
composer install
```

7- Lancer le script websocket dans l'invite de commmande du conteneur application

```sh
php bin/websocket.php
```

8- Dans votre navigateur rendez vous a l'url http://localhost:80 afin d'accédez a l'application.
## Tech Stack

**Client:** JS vanilla

**Server:** PHP

**Librairie** Ratchet-PHP

Ratchet-PHP permet la communication du client et du navigateur.

Le client envoie un message au serveur qui écoute sur le port 9001 et ensuite traite le message pour envoyer une réponse appropriée.

## A venir

- Ne pas être en mesure d'obtenir un score négatif.
- Arrêtez le jeu automatiquement lorsqu'une personne obtient 10 points.
- Ajout de plus de style

