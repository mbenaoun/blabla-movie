# Blabla Movie - Mhemed BEN AOUN pour Kreactive

Kreactive m'a demandé de réaliser une API permettant de : 

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
5. Mettre les config (DB et Redis) dans le fichier .env à la racine du projet

## URI de l'API

1. YOUR_SERVER_HOST/v1/users | POST => Pour créer un user.

Exemple de body : 

{
	"pseudo":"TEST",
	"email":"TEST@TEST.FR",
	"dateOfBirth":"2019-12-25"
}

2. YOUR_SERVER_HOST/v1/users/{user_id}/movies | GET => Pour lister les films choisis par un utilisateur (user_id).

3. YOUR_SERVER_HOST/v1/survey | POST => enregistrer le choix d'un film d'un utilisateur

Exemple de body : 

{
	"userId": 40,  
	"movieId":1, => Permet de récupérer un film
	"movieTitle":"tata" => Cherche le film sur l'api http://omdbapi.com/ et créé le film dans la db
}

Il faut soit la clé "movieId" ou "movieTitle" !

4. YOUR_SERVER_HOST/v1/survey/{user_id}/{movie_id:optional} | DELETE => supprime le choix d'un film d'un utilisateur ou tout les choix de film d'un utilisateur

5. YOUR_SERVER_HOST/v1/survey/best-movie | GET => retourner le meilleur film selon l'ensemble des utilisateurs

6. YOUR_SERVER_HOST/v1/movies/users | GET => lister les utilisateurs ayant choisi un film

L'API retourne des datas en JSON ou XML
