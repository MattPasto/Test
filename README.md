# Documentazione esercitazione

Questa è una piccola guida che funge da introduzione al progetto.
Verranno introdotti:
- Dipendenze e avvio progetto 
- Tecnologie utilizzate
- Elementi pratici del progetto

## Dipendenze progetto

Per far girare il progetto l'unica dipendenza necessaria è docker-engine per far partire partire i container. Per far partire il progetto basta posizionarsi nella cartella del progetto e lanciare lo script buildApplication.sh 
`sh buildApplication.sh`. Quando lo script terminerà l'interfaccia dell'applicazione sarà contattabile a questo indirizzo [link](http://localhost:3000/). 

## Tecnologie utilizzate

- Docker: per creare l'ambiente di sviluppo
- Laravel: per la scrittura del backend
- React: per l'interfaccia
- Mysql
- Apache

## Container presenti 
(Se ci sono già dei servizi attivi sulle porte che occupano i container queste ultime possono essere cambiate nei file .envdove sono presenti i file di configurazione di docker)
Una volta terminato lo script per far partire il progetto con lo script citato all'inizio, ci sarà un gruppo di container chiamato webapp questo contiene:
- project_react, applicazione react disponibile alla porta 3000 del proprio pc
- project_laravel, progetto laravel porta 8081
- dev_db, (3307) database in mysql dove sono presenti le tabelle del progetto
- dev_test, (3308) databese utilizzato per eseguire i test sulle api
- phpmyadmin, (8080) versione di phpMyAd utilizzabile per collegarsi ai db, le connessioni sono già impostate

## Guida Backend

Nel progetto è presente una collection postMan dove sono presenti le rotte api con dei payload d'esempio.
Le rotte sono definite nel file `/routes/api.php`.
Mentre i controller ed i modelli sono presenti a questi percorsi : `/app/Http/Controllers/` , `/app/Models`.
I test delle api sono presenti a questo percorso: `/tests/Feature/`, sono eseguibili con questo comando all'interno del container project_laravel `php artisan test`

## Guida Frontend

I componenti utilizzati per la scrittura dell'interfaccia sono presenti nella cartella src del progetto in particolare nella sottocartelle `/Components` `/Pages` `/Utility`

