docker login --username $DOCKER_USERNAME --password $DOCKER_PASSWORD
docker-compose --project-name obsession push workspace apache2 mailhog php-fpm php-worker mysql redis elasticsearch kibana
