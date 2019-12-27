# Blabla Movie - Mhemed BEN AOUN pour Kreactive

L'Agence Kreactive m'a demandé de réaliser une API permettant de : 

- créer un utilisateur (pseudo, email unique, date de naissance, date de création en base de données)
- enregistrer le choix d'un film d'un utilisateur
- supprimer le choix d'un film d'un utilisateur
- lister les choix de film d'un utilisateur
- lister les utilisateurs ayant choisi un film
- retourner le meilleur film selon l'ensemble des utilisateurs

## Installation

1. Cloner le projet
2. Installer les bundles avec Composer
3. Avoir un serveur redis
4. Avoir une db mysql avec une base de donnée
5. Renseigner les config (DB et Redis) dans le fichier .env à la racine du projet

```yaml
###> doctrine/doctrine-bundle ###
DB_HOST=__YOUR_DB_HOST__
DB_PORT=__YOUR_DB_PORT__
DB_SCHEMA=__YOUR_DB_SCHEMA__
DB_USER=__YOUR_DB_USER__
DB_PWD=__YOUR_DB_PWD__
###< doctrine/doctrine-bundle ###

###> settings redis ###
REDIS_HOST=__YOUR_REDIS_HOST__
REDIS_PORT=__YOUR_REDIS_PORT__
###< settings redis ###
```

## URI de l'API

1. YOUR_SERVER_HOST/v1/users

    - URI METHOD : POST
    
    - Description : URI pour créer un user.
    
    - Exemple de body : 

    ```json
    {
	      "pseudo":"TEST",
	      "email":"TEST@TEST.FR",
	      "dateOfBirth":"2019-12-25"
    }
    ```

2. YOUR_SERVER_HOST/v1/users/*{user_id}*/movies

    - URI METHOD : GET
    
    - Description : URI pour lister les films choisis par un utilisateur (user_id).

3. YOUR_SERVER_HOST/v1/survey

    - URI METHOD : POST
    
    - Description : URI pour enregistrer le choix d'un film d'un utilisateur.

    - Exemple de body : 

    ```json
    {
       "userId": 40,
       "movieId":1,
       "movieTitle":"tata"
    }
    ```

    - Notes : 
        - "movieId" : Permet de récupérer un film.
        - "movieTitle" : Cherche le film sur l'api http://omdbapi.com/ et créé le film dans la db
        - Il faut dans le body soit la clé "movieId" ou "movieTitle" !

4. YOUR_SERVER_HOST/v1/survey/*{user_id}*/*{movie_id:optional}*

    - URI METHOD : DELETE
    
    - Description : URI pour supprimer le choix d'un film d'un utilisateur ou tout les choix de film d'un utilisateur.

5. YOUR_SERVER_HOST/v1/survey/best-movie

    - URI METHOD : GET
    
    - Description : URI pour retourner le meilleur film selon l'ensemble des utilisateurs

6. YOUR_SERVER_HOST/v1/movies/users

    - URI METHOD : GET
    
    - Description : URI pour lister les utilisateurs ayant choisi un film.

**L'API retourne des datas en JSON ou XML**
