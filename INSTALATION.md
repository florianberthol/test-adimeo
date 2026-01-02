## Installation
Pour installer le projet, il faut lancer les commandes suivantes :

```
docker compose build
docker compose up -d
docker compose exec composer install
docker compose exec php-test bin/console do:da:create
docker compose exec php-test bin/console do:mi:mi
docker compose exec php-test bin/console importmap:install
docker compose exec php-test bin/console asset-map:compile
```

Il faut ensuite configurer les variables d'environnements dans le fichier `.env.local` :

```
GOOGLE_CLIENT_ID=""
GOOGLE_CLIENT_SECRET=""
NASA_API_KEY=""
```

Pour récuperer les images de la NASA, il faut lancer la commande suivante :

```docker compose exec php-test bin/console app:image:get```

Le projet est accessible à l'adresse [http://localhost](http://localhost)

## Choix techniques
 1. Conteneurisation avec Docker, serveur http frankenphp et base de donné mariadb.
 2. Utilisation de symfony 7.4
 3. Connection google avec league/oauth2-google (https://oauth2-client.thephpleague.com/)
 4. HTTP Client pour l'appel API
 5. AssetMapper pour la gestion des assets
 6. Bootstrap 5 pour le css