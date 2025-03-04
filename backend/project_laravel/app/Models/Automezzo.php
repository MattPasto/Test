<?php

namespace App\Models;

use App\Traits\UtilsTrait;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Automezzo extends Model
{
    use UtilsTrait;
    protected $table = "automezzo";
    protected $fillable = [
        "filiale_id",
        "codice",
        "targa",
        "marca",
        "modello"
    ];

    public $timestamps = true;

    const FILIALE_ERROR = "Inserire una filiale valida";
    const AUTOMEZZO_ERROR = "Inserire un Automezzo valido";

    /**
     * Metodo che permette di creare un record di tipo automezzo
     * @param array $attributes :[
     *                              "filiale_id" : 2,
     *                              "codice"  : "AAAA-2222",
     *                              "targa"   : "JDJDK789",
     *                              "marca"   : "paulo",
     *                              "modello" : "QCF3"
     *                                 ]
     * @throws Exception
     * @return array{code: int, response: array}
     */
    public static function crea(array $attributes): array
    {

        // Controllo che l'id della filiale sia valido. Faccio qui il controllo perché anche se i validatori di laravel gestiscono questo controllo 
        // non hanno di base una gestione ottimale dell'eccezione generata.

        try {

            // Controllo l'id

            $check = Filiale::find(id: $attributes["filiale_id"]);

            if (!$check) {
                throw new Exception(message: self::FILIALE_ERROR, code: self::HTTP_NOT_FOUND);
            }
            self::create(attributes: $attributes);

            return [
                "code" => self::HTTP_CREATED,
                "response" => [
                    "message" => "Record inserito"
                ]
            ];
        } catch (QueryException $qe) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Ritorna una lista di Automezzi. Non sono presenti filtri o paginazione perché non erano richiesti
     * 
     * @throws Exception
     * @return array{code: int, response: array}
     */
    public static function lista(): array
    {
        try {
            $list = self::select("automezzo.*", "filiale.codice as filiale_codice")
                ->join("filiale", "automezzo.filiale_id", "=", "filiale.id")
                ->get()->toArray();

            return [
                "code" => self::HTTP_OK,
                "response" => [
                    'data' => $list
                ]
            ];
        } catch (QueryException $qe) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Recupera le informazioni di un automezzo
     * @param int $id
     * @throws Exception
     * @return array{code: int, response: array}
     */
    public static function dettaglio(int $id): array
    {
        try {

            // Prima di recuperare i dettagli verifico che l'id sia valido 
            if (!self::find(id: $id)) {
                throw new Exception(message: self::AUTOMEZZO_ERROR, code: self::HTTP_NOT_FOUND);
            }

            $details = self::select(
                "automezzo.*",
                "filiale.indirizzo",
                "filiale.città",
                "filiale.cap",
                "filiale.codice as filiale_codice",
                "filiale.id as filiale_id"
            )
                ->join("filiale", "automezzo.filiale_id", "=", "filiale.id")
                ->where('automezzo.id', '=', $id)
                ->get()->toArray();

            return [
                "code" => self::HTTP_OK,
                "response" => [
                    'data' => $details
                ]
            ];
        } catch (QueryException $qe) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Metodo che permette di aggiornare un record di tipo automezzo
     * @param array $attributes :[
     *                  "id": 1
     *                  "filiale_id": 1,
     *                  "codice" : "AAA-222",
     *                  "targa" : "ARGD46",
     *                  "marca" : "Yoshi"
     *                  "modello" : "JIEIN-11t"
     *                                 ]
     * @throws Exception 
     * @return array{code: int, response: array}
     */
    public static function aggiorna(array $attributes): array
    {
        try {
            // Controllo che l'id dell'automezzo sia valido 
            $record = self::find(id: $attributes['id']);

            if (!$record) {
                throw new Exception(message: self::AUTOMEZZO_ERROR, code: self::HTTP_NOT_FOUND);
            }

            foreach ($attributes as $field => $value) {
                $record->$field = $value;
            }

            // Controllo che la filiale esista 
            $check = Filiale::find(id: $record->filiale_id);

            if (!$check) {
                throw new Exception(message: self::FILIALE_ERROR, code: self::HTTP_NOT_FOUND);
            }

            // Se ci sono problemi nel salvataggio genero eccezione
            if (!$record->save()) {
                throw new Exception(message: self::SAVE_ERROR, code: self::HTTP_INTERNAL_SERVER_ERROR);
            }

            return [
                "code" => self::HTTP_OK,
                "response" => [
                    "message" => "Record aggiornato"
                ]
            ];
        } catch (QueryException $qe) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Elimina un singolo record Automezzo
     * @param array $attributes ["id" : 1]
     * @throws Exception Se non vengono cancellati record
     * @return  array{code: int, response: array}
     */
    public static function elimina(array $attributes): array
    {
        try {

            $deleted = self::where("id", $attributes["id"])->delete();

            if ($deleted === 0) {
                throw new Exception(message: self::AUTOMEZZO_ERROR, code: self::HTTP_NOT_FOUND);
            }

            return [
                "code" => self::HTTP_OK,
                "response" => [
                    "message" => "Record eliminato"
                ]
            ];
        } catch (QueryException $e) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
