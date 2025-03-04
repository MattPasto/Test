<?php

namespace Tests\Feature;

use App\Models\Filiale;
use App\Traits\UtilsTrait;
use Tests\TestCase;

class FilialeUpdateTest extends TestCase
{
    use UtilsTrait;
    /**
     * Test per l'aggiornamento completo di una Filiale
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        //Recupero una Filiale
        $filiale_id = Filiale::first()->id;

        $response = $this->put(uri: '/api/filiale/update', data: [
            'id' => $filiale_id,
            'codice' => "893120-KALST",
            'indirizzo' => "Grove Street",
            'città' => "Los Santos",
            'cap' => "12345"
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
    public function testUpdateFilialeError(): void
    {
        //Recupero una filiale non esistente
        $filiale_id = Filiale::orderBy('id', 'desc')->first()->id + 1;

        $response = $this->put(uri: '/api/filiale/update', data: [
            'id' => $filiale_id,
            'codice' => "893120-KALST",
            'indirizzo' => "Grove Street",
            'città' => "Los Santos",
            'cap' => "12345"
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
        $response = $this->put(uri: '/api/filiale/update', data: [
            'bho' => 'bho'
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_UNPROCESSABLE_CONTENT)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }
}


