<?php

namespace Tests\Feature;

use App\Traits\UtilsTrait;
use App\Models\Automezzo;
use Tests\TestCase;

class AutomezzoDetailsTest extends TestCase
{
    use UtilsTrait;

    /**
     * Test per i dettagli di un automezzo
     * @return void
     */
    public function testDetailsSuccess(): void
    {
        // Recupero l'id di un automezzo
        $automezzo_id = Automezzo::first()->id;

        $response = $this->get(uri: '/api/automezzo/details/' . $automezzo_id);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_OK)->assertJson(value: [
            "data" => true
        ]);

        $this->assertIsArray(actual: $body['data']);
        $this->assertIsArray(actual: $body['data'][0]);
        $this->assertIsInt(actual: $body['data'][0]['id']);
        $this->assertIsInt(actual: $body['data'][0]['filiale_id']);
        $this->assertIsString(actual: $body['data'][0]['codice']);
        $this->assertIsString(actual: $body['data'][0]['indirizzo']);
        $this->assertIsString(actual: $body['data'][0]['città']);
        $this->assertIsString(actual: $body['data'][0]['cap']);
        $this->assertIsString(actual: $body['data'][0]['targa']);
        $this->assertIsString(actual: $body['data'][0]['marca']);
        $this->assertIsString(actual: $body['data'][0]['modello']);
        $this->assertIsString(actual: $body['data'][0]['created_at']);
        $this->assertIsString(actual: $body['data'][0]['updated_at']);
        $this->assertIsString(actual: $body['data'][0]['filiale_codice']);
    }

    /**
     * Test con un errore nel parametro nell'url
     * @return void
     */
    public function testDetailsPayloadError(): void
    {

        $response = $this->get(uri: '/api/automezzo/details/paulo');

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_UNPROCESSABLE_CONTENT)->assertJson([
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per verificare la risposta con un id inesistente
     * @return void
     */
    public function testDetailsAutomezzoError(): void
    {

        // Recupero l'id di un automezzo
        $automezzo_id = Automezzo::orderBy('id', 'desc')->first()->id + 1;

        $response = $this->get(uri: '/api/automezzo/details/'.$automezzo_id);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_NOT_FOUND)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }
}
