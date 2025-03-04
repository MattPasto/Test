<?php

namespace Tests\Feature;

use App\Models\Filiale;
use App\Traits\UtilsTrait;
use Tests\TestCase;

class FilialeDetailsTest extends TestCase
{
    use UtilsTrait;

    /**
     * Test per i dettagli di un automezzo
     * @return void
     */
    public function testDetailsSuccess(): void
    {
        // Recupero l'id di un automezzo
        $filiale_id = Filiale::first()->id;

        $response = $this->get(uri: '/api/filiale/details/' . $filiale_id);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_OK)->assertJson(value: [
            "data" => true
        ]);

        $this->assertIsArray(actual: $body['data']);
        $this->assertIsArray(actual: $body['data']['filiale']);
        $this->assertIsArray(actual: $body['data']['automezzi']);
        $this->assertIsArray(actual: $body['data']['automezzi'][0]);
        $this->assertIsInt(actual: $body['data']['filiale']['id']);
        $this->assertIsString(actual: $body['data']['filiale']['codice']);
        $this->assertIsString(actual: $body['data']['filiale']['indirizzo']);
        $this->assertIsString(actual: $body['data']['filiale']['città']);
        $this->assertIsString(actual: $body['data']['filiale']['cap']);
        $this->assertIsString(actual: $body['data']['filiale']['created_at']);
        $this->assertIsString(actual: $body['data']['filiale']['updated_at']);
        $this->assertIsInt(actual: $body['data']['automezzi'][0]['id']);
        $this->assertIsInt(actual: $body['data']['automezzi'][0]['filiale_id']);
        $this->assertIsString(actual: $body['data']['automezzi'][0]['codice']);
        $this->assertIsString(actual: $body['data']['automezzi'][0]['targa']);
        $this->assertIsString(actual: $body['data']['automezzi'][0]['marca']);
        $this->assertIsString(actual: $body['data']['automezzi'][0]['modello']);
        $this->assertIsString(actual: $body['data']['automezzi'][0]['created_at']);
        $this->assertIsString(actual: $body['data']['automezzi'][0]['updated_at']);
    }

    /**
     * Test con un errore nel parametro nell'url
     * @return void
     */
    public function testDetailsPayloadError(): void
    {

        $response = $this->get(uri: '/api/filiale/details/paulo');

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
        $filiale_id = Filiale::orderBy('id', 'desc')->first()->id + 1;

        $response = $this->get(uri: '/api/filiale/details/'.$filiale_id);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_NOT_FOUND)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }
}

