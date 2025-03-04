#!/bin/bash

cd /project_react;

npm install --force;

npm run start # Faccio partire l'applicazione

tail -f /dev/null # Mantengo in esecuzione il container