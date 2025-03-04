<?php

namespace Tests\Feature;

use App\Models\Automezzo;
use App\Models\Filiale;
use App\Traits\UtilsTrait;
use Tests\TestCase;

class AutomezzoUpdateTest extends TestCase
{
    use UtilsTrait;
    /**
     * Test per l'aggiornamento completo di un Automezzo
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        //Recupero un automezzo
        $automezzo = Automezzo::first();

        //Recupero una filiale diversa da quella dell'automezzo
        $filiale_id = Filiale::where('id', '!=', $automezzo->filiale_id)->first()->id;

        $response = $this->put(uri: '/api/automezzo/update', data: [
            'id' => $automezzo->id,
            'filiale_id' => $filiale_id,
            'codice' => "893120-KALST",
            'targa' => "FOLFA245",
            'marca' => "Puma",
            'modello' => "A-23s"
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_OK)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per verificare il comportamento con un id non esistente
     * @return void
     */
    public function testUpdateAutomezzoError(): void
    {
        //Recupero un automezzo non esistente
        $automezzo_id = Automezzo::orderBy('id', 'desc')->first()->id + 1;

        //Recupero una filiale diversa da quella dell'automezzo
        $filiale_id = Filiale::first()->id;

        $response = $this->put(uri: '/api/automezzo/update', data: [
            'id' => $automezzo_id,
            'filiale_id' => $filiale_id,
            'codice' => "893120-KALST",
            'targa' => "FOLFA245",
            'marca' => "Puma",
            'modello' => "A-23s"
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_NOT_FOUND)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per verificare il comportamento con un filiale_id non esistente
     * @return void
     */
    public function testUpdateFilialeError(): void
    {
        //Recupero un automezzo non esistente
        $automezzo_id = Automezzo::first()->id;

        //Recupero una filiale diversa da quella dell'automezzo
        $filiale_id = Filiale::orderBy('id', 'desc')->first()->id + 1;

        $response = $this->put(uri: '/api/automezzo/update', data: [
            'id' => $automezzo_id,
            'filiale_id' => $filiale_id,
            'codice' => "893120-KALST",
            'targa' => "FOLFA245",
            'marca' => "Puma",
            'modello' => "A-23s"
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_NOT_FOUND)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per verificare il comportamento con un payload biricchino
     * @return void
     */
    public function testUpdatePayloadError(): void
    {
        $response = $this->put(uri: '/api/automezzo/update', data: [
            'id' => "FAFNAF",
            'filiale_id' => "SLITUP",
            'codice' => "893120-KALST",
            'targa' => "FOLFA245",
            'marca' => "Puma",
            'modello' => "A-23s"
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_UNPROCESSABLE_CONTENT)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }
}
