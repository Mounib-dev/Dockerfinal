Le Dockerfile contient le code nécessaire pour construire l'image Docker

Ensuite on ajoute le compose.yaml pour lancer les services web (qui correspond à une image Apache avec PHP) qu'on construit depuis le Dockerfile en le spécficiant dans le fichier compose)

On fait en sorte d'avoir un volume dans le service db pour avoir ensuite des données dedans.

Pour que les deux services puissent communiquer entre eux, je définis un réseau au début de mon fichier compose où je le nomme et le mets de type bridge => Ensuite dans chaque service, je dois définir networks avec le même nom du network global défini pour que les deux services arrivent ensuite à communiquer entre eux.

Pour avoir des données de démarrage, on peut ajouter au Dockerfile COPY init.sql /docker-entrypoint-initdb.d/

Ensuite, on va dans le service db dans compose.yaml, on ajoute la ligne suivante dans le service : init_db: /docker-entrypoint-initdb.d/

Pour le déploiement avec Docker Swarm, on peut choisir une image alpine pour avoir une distribution plus légère pour de meilleures performances

On pourrait utiliser cela
FROM php:8.2-alpine AS builder

WORKDIR /app

COPY (tous les fichiers)

RUN apk --no-cache add \
 $PHPIZE_DEPS \
 && pecl install apcu \
 && docker-php-ext-enable apcu \
 && apk del $PHPIZE_DEPS \
 && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && php -r "unlink('composer-setup.php');" \
 && composer install --no-dev --no-scripts --no-autoloader \
 && composer dump-autoload --optimize
