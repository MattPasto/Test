<?php

namespace App\Traits;

trait UtilsTrait
{
    // COSTANTI DI RISPOSTA HTTP
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NOT_FOUND = 404;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNPROCESSABLE_CONTENT = 422;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    // COSTANTI GENERICHE
    const ERROR_DB      = "Problemi con la connessione al DB";
    const SAVE_ERROR    = "Problemi nel salvataggio del record";
    const NOT_NUMBER_ERROR = "L'id selezionato non è valido";
}