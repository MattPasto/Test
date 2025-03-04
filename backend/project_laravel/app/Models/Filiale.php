<?php

namespace App\Models;

use App\Traits\UtilsTrait;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Filiale extends Model
{
    use HasFactory;
    use UtilsTrait;
    protected $table = "filiale";

    protected $fillable = [
        "codice",
        "indirizzo",
        "città",
        "cap"
    ];

    public $timestamps = true;

    const FILIALE_ERROR = "Filiale non valida";
    const FILIALE_DELETE_ERROR = "La filiale selezionata ha degli automezzi associati";

    /**
     * Metodo che permette di creare un record di tipo filiale
     * @param array $attributes :[
     *                  "codice" : "BBB-111",
     *                  "indirizzo" : "via le mani dal naso",
     *                  "città" : "Sava"
     *                  "cap" : "12345"
     *                                 ]
     * @throws \Illuminate\Database\QueryException Se ci sono problemi legati al db
     * @return array{code: int, response: array}
     */
    public static function crea(array $attributes): array
    {

        //Essendo che il payload è stato già validato dal controller e non ci sono particolari accorgimenti data la natura dell'esercizio, creo
        //direttamente il record e catturo un eventuale QueryException per non buttare in output un errore non gestito

        try {
            self::create(attributes: $attributes);

            return [
                "code" => self::HTTP_CREATED,
                "response" => ["message" => "Record inserito"]
            ];
        } catch (QueryException $qe) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Metodo che permette di aggiornare un record di tipo filiale
     * @param array $attributes :[
     *                  "id" : 1
     *                  "codice" : "BBB-111",
     *                  "indirizzo" : "Via Angelo n21",
     *                  "città" : "Sava"
     *                  "cap" : "12345"
     *                                 ]
     * @throws Exception
     * @return array{code: int, response: array}
     */
    public static function aggiorna(array $attributes): array
    {
        try {
            // Controllo che l'id della filiale sia valido 
            $record = self::find(id: $attributes['id']);

            if (!$record) {
                throw new Exception(message: self::FILIALE_ERROR, code: self::HTTP_NOT_FOUND);
            }

            foreach ($attributes as $field => $value) {
                $record->$field = $value;
            }

            // Se ci sono problemi nel salvataggio genero eccezione
            if (!$record->save()) {
                throw new Exception(message: self::SAVE_ERROR, code: self::HTTP_INTERNAL_SERVER_ERROR);
            }

            return [
                "code" => self::HTTP_OK,
                "response" => ["message" => "Record aggiornato"]
            ];
        } catch (QueryException $qe) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Ritorna una lista di Filiali. Non sono presenti filtri o paginazione perché non erano richiesti
     * 
     * @throws Exception
     * @return array{code: int, response: array}
     */
    public static function lista(): array
    {
        try {
            $list = Filiale::select('filiale.*', DB::RAW('COUNT(automezzo.id) as automezzi_tot')) // Aggiungo il numero di automezzi della filiale
                ->leftJoin('automezzo', 'filiale.id', '=', 'automezzo.filiale_id')
                ->groupBy('filiale.id')
                ->get()->toArray();

            return [
                "code" => self::HTTP_OK,
                "response" => ['data' => $list]
            ];
        } catch (QueryException $qe) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Recupera le informazioni di una filiale
     * @param int $id
     * @throws Exception
     * @return array{code: int, response: array}
     */
    public static function dettaglio(int $id): array
    {
        try {

            $filiale = self::where('id', '=', $id)->first();

            // Se $filiale è null lancio eccezione
            if (!$filiale) {
                throw new Exception(message: self::FILIALE_ERROR, code: self::HTTP_NOT_FOUND);
            }

            // Recupero informazioni sugli automezzi della filiale
            $automezzi = Automezzo::select('*')
                ->where('filiale_id', '=', $id)
                ->get()->toArray();
            return [
                "code" => self::HTTP_OK,
                "response" => [
                    'data' => [
                        'filiale' => $filiale->toArray(),
                        'automezzi' => $automezzi
                    ]
                ]
            ];
        } catch (QueryException $qe) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Elimina un singolo record Filiale. Una filiale potrà essere elmiminata solo se non ha automezzi
     * 
     * @param array $attributes ["id" : 1]
     * @throws Exception 
     * @return array{code: int, response: array}
     */
    public static function elimina(array $attributes): array
    {
        try {

            // Controllo che la Filiale non abbia automezzi associati
            $check = Automezzo::where('filiale_id', '=', $attributes['id'])->first();

            if ($check) {
                throw new Exception(message: self::FILIALE_DELETE_ERROR, code: self::HTTP_BAD_REQUEST);
            }
            $deleted = self::where("id", $attributes["id"])->delete();

            if ($deleted === 0) {
                throw new Exception(message: self::FILIALE_ERROR, code: self::HTTP_NOT_FOUND);
            }

            return [
                "code" => self::HTTP_OK,
                "response" => ["message" => "Record eliminato"]
            ];
        } catch (QueryException $e) {
            throw new Exception(message: self::ERROR_DB, code: self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
