<?php

namespace Tests\Feature;

use App\Models\Filiale;
use App\Traits\UtilsTrait;
use Tests\TestCase;

class AutomezzoCreateTest extends TestCase
{
    use UtilsTrait;
    /**
     * Test per la creazione di un Automezzo
     * @return void
     */
    public function testCreateSuccess(): void
    {
        //Prendo la prima filiale presente nel db di test
        $filiale_id = Filiale::first()->id;

        $response = $this->post(uri: '/api/automezzo/create', data: [
            'filiale_id' => $filiale_id,
            'codice' => "893120-KALST",
            'targa' => "FOLFA245",
            'marca' => "Puma",
            'modello' => "A-23s"
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_CREATED)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per verificare come il metodo risponde con un payload errato
     * @return void
     */
    public function testCreatePayloadError(): void
    {
        $response = $this->post(uri: '/api/automezzo/create', data: [
            'filiale_id' => "aa",
            'codice' => "893120-KALST",
            'targa' => "FOLFA245",
            'marca' => "Puma",
            'modello' => "A-23s"
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_UNPROCESSABLE_CONTENT)->assertJson([
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per la verificare con una Filiale inesistente
     * @return void
     */
    public function testCreateFilialeError(): void
    {
        //Recupero un id che sono sicuro non esista
        $filiale_id = Filiale::orderBy('id', 'desc')->first()->id + 1;

        $response = $this->post(uri: '/api/automezzo/create', data: [
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
}
