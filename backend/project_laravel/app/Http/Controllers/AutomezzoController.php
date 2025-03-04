<?php

namespace App\Http\Controllers;

use App\Traits\UtilsTrait;
use Illuminate\Http\Request;
use App\Models\Automezzo;
use Illuminate\Http\JsonResponse;
use Exception;

class AutomezzoController extends Controller
{
    use UtilsTrait;
    /**
     * Costante con le regole di validazione per la creazione 
     * @var array
     */
    const RULES_CREATE = [
        'filiale_id' => 'required|integer',
        'codice' => 'required|string|max:255',
        'targa' => 'required|string|max:255',
        'marca' => 'required|string|max:255',
        'modello' => 'required|string|max:255',
    ];

    /**
     * Costante con le regole di validazione per la modifica 
     * @var array
     */
    const RULES_UPDATE = [
        'id' => 'required|integer',
        'filiale_id' => 'integer',
        'codice' => 'string|max:255',
        'targa' => 'string|max:255',
        'marca' => 'string|max:255',
        'modello' => 'string|max:255',
    ];


    /**
     * Costante con le regole di validazione l'eliminazione
     * @var array
     */
    const RULES_DELETE = [
        'id' => 'required|integer',
    ];


    /**
     * Crea un record Automezzo
     * @param \Illuminate\Http\Request $request:
     *              {
     *                  "filiale_id": 1,
     *                  "codice" : "AAA-111",
     *                  "targa" : "ARGD46",
     *                  "marca" : "Lidl"
     *                  "modello" : "ASFAF-12t"
     *                    }
     * @return JsonResponse
     */
    public static function create(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate(
                rules: self::RULES_CREATE,
            );

            // Se la validazione passa creo l'automezzo
            $res = Automezzo::crea(attributes: $validated);

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
     * Ritorna una lista di record Automezzo
     * 
     *  @return JsonResponse
     */
    public static function list(): JsonResponse
    {
        try {
            $res = Automezzo::lista();

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
     * Ritorna informazioni su uno specifico automezzo
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

            $res = Automezzo::dettaglio(id: $id);

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
     * Modifica un record Automezzo
     * @param \Illuminate\Http\Request $request:
     *              {
     *                  "id": 1
     *                  "filiale_id": 1,
     *                  "codice" : "AAA-222",
     *                  "targa" : "ARGD46",
     *                  "marca" : "Yoshi"
     *                  "modello" : "JIEIN-11t"
     *                    }
     * @return JsonResponse
     */
    public static function update(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate(
                rules: self::RULES_UPDATE,
            );

            // Se la validazione passa aggiorno l'automezzo
            $res = Automezzo::aggiorna(attributes: $validated);

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
     * Elimina un singolo record Automezzo
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
            $res = Automezzo::elimina(attributes: $validated);
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
