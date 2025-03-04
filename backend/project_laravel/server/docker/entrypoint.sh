#!/bin/bash

# Installo le dipendenze del progetto
cd $CONTAINER_DIR;

composer install;

# Aggiusto i permessi altrimenti apache esplode
chmod -R 777 storage;
chmod -R 777 bootstrap/cache;

# Avvio apache
/usr/local/bin/apache2-foreground;


