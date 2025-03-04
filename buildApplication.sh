#!/bin/bash

# Eseguo il build e up dei container
echo "Eseguo docker compose build"
docker compose build

echo "Eseguendo docker compose up -d"
docker compose up -d

# Aspetto per far partire i servizi, (colpa del mio pc patata :/ )
echo "Aspetto per far partire i servizi ZZZ...." 
sleep 30;

# Mi collego al container project_laravel ed eseguo migrazioni e seeder (Lo faccio qui così che al termine di questo script sia tutto pronto)
echo "Eseguo migrazioni e seeder del progetto"
docker exec -it project_laravel bash -c "cd /project_laravel && php artisan migrate && php artisan db:seed"

echo "Buil Terminato!"
