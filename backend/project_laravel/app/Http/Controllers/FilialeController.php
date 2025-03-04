<?php

namespace App\Http\Controllers;

use App\Traits\UtilsTrait;
use Illuminate\Http\Request;
use App\Models\Filiale;
use Illuminate\Http\JsonResponse;
use Exception;

class FilialeController extends Controller
{
    use UtilsTrait;
    
    /**
     * Costante con le regole di validazione per la creazione 
     * @var array
     */
    const RULES_CREATE = [
        'codice' => 'required|string|max:255',
        'indirizzo' => 'required|string|max:255',
        'città' => 'required|string|max:255',
        'cap' => 'required|string|max:255',
    ];

    /**
     * Costante con le regole di validazione per la modifica 
     * @var array
     */
    const RULES_UPDATE = [
        'id' => 'required|integer',
        'codice' => 'string|max:255',
        'indirizzo' => 'string|max:255',
        'città' => 'string|max:255',
        'cap' => 'string|max:255',
    ];

    /**
     * Costante con le regole di validazione l'eliminazione
     * @var array
     */
    const RULES_DELETE = [
        'id' => 'required|integer',
    ];
    /**
     * Crea un record Filiale
     * @param \Illuminate\Http\Request $request:
     *              {
     *                  "codice" : "BBB-111",
     *                  "indirizzo" : "via le mani dal naso",
     *                  "città" : "Sava"
     *                  "cap" : "12345"
     *                    }
     * @return JsonResponse
     */
    public static function create(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate(
                rules: self::RULES_CREATE,
            );

            // Se la validazione passa creo la filiale
            $res = Filiale::crea(attributes: $validated);

        } catch (Exception $e) {
            $res = [
                'response' => ['message' => $e->getMessage()],
                'code' => $e->getCode() === 0 ? $e->status : $e->getCode(),
            ];
        }

        return response()->json(
            data: $res['response'],
            status: $res['code']
        );
    }

    /**
     * Ritorna una lista di record Filiale
     * 
     *  @return JsonResponse
     */
    public static function list(): JsonResponse
    {
        try {
            // Se la validazione passa creo l'automezzo
            $res = Filiale::lista();

        } catch (Exception $e) {
            $res = [
                'response' => ['message' => $e->getMessage()],
                'code' => $e->getCode() === 0 ? $e->status : $e->getCode(),
            ];
        }

        return response()->json(
            data: $res['response'],
            status: $res['code']
        );
    }

     /**
     * Ritorna informazioni per una specifica Filiale
     * 
     *  @param $id recuperato dal link
     *  @return JsonResponse
     */
    public static function details($id = 0): JsonResponse
    {
        try {
            // Controllo che l'id ricevuto sia un numero
            if (!is_numeric(value: $id) || $id <= 0) {
                throw new Exception(message: self::NOT_NUMBER_ERROR, code: self::HTTP_UNPROCESSABLE_CONTENT);
            }

            $res = Filiale::dettaglio(id: $id);

        } catch (Exception $e) {
            $res = [
                'response' => ['message' => $e->getMessage()],
                'code' => $e->getCode(),
            ];
        }

        return response()->json(
            data: $res['response'],
            status: $res['code']
        );
    }

    /**
     * Modifica un record Filiale
     * @param \Illuminate\Http\Request $request:
     *              {
     *                  "id" : 1
     *                  "codice" : "AAA-111",
     *                  "indirizzo" : "via le mani dal naso",
     *                  "città" : "Sava"
     *                  "cap" : "12345"
     *                    }
     * @return JsonResponse
     */
    public static function update(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate(
                rules: self::RULES_UPDATE,
            );

            // Se la validazione passa creo la filiale
            $res = Filiale::aggiorna(attributes: $validated);

        } catch (Exception $e) {
            $res = [
                'response' => ['message' => $e->getMessage()],
                'code' => $e->getCode() === 0 ? $e->status : $e->getCode(),
            ];
        }

        return response()->json(
            data: $res['response'],
            status: $res['code']
        );
    }

    /**
     * Elimina un singolo record Filiale. Una filiale potrà essere elmiminata solo se non ha automezzi
     * @param \Illuminate\Http\Request $request ["id" : 1]
     * @return JsonResponse
     */
    public static function delete(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate(
                rules: self::RULES_DELETE,
            );

            // Se la validazione passa smolecolo l'automezzo
            $res = Filiale::elimina(attributes: $validated);
        } catch (Exception $e) {
            $res = [
                'response' => ['message' => $e->getMessage()],
                'code' => $e->getCode() === 0 ? $e->status : $e->getCode(),
            ];
        }

        return response()->json(
            data: $res['response'],
            status: $res['code']
        );
    }
}
