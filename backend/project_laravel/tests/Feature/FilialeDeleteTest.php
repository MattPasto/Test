<?php

namespace Tests\Feature;

use App\Models\Filiale;
use App\Traits\UtilsTrait;
use Tests\TestCase;

class FilialeDeleteTest extends TestCase
{
    use UtilsTrait;
    /**
     * Test per la distruzione di una Filiale che non ha automezzi
     * @return void
     */
    public function testDeleteSuccess(): void
    {
        // Prendo un automezzo da sacrificare 
        $filiale_id = Filiale::factory()->create()->id;

        $response = $this->delete(uri: '/api/filiale/delete', data: [
            'id' => $filiale_id
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_OK)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per la verificare il comportamentocon una filiale inesistente
     * @return void
     */
    public function testDeleteFilialeError(): void
    {
        //Recupero un id che sono sicuro non esista
        $filiale_id = Filiale::orderBy('id', 'desc')->first()->id + 1;

        $response = $this->delete(uri: '/api/filiale/delete', data: [
            'id' => $filiale_id
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_NOT_FOUND)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per la salvaguardia degli automezzi
     * @return void
     */
    public function testDeleteAutomezziError(): void
    {
        // Prendo una filiale che ha degli automezzi
        $filiale_id = Filiale::first()->id;

        $response = $this->delete(uri: '/api/filiale/delete', data: [
            'id' => $filiale_id
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_BAD_REQUEST)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per verificare come il metodo risponde con un payload errato
     * @return void
     */
    public function testDeletePayloadError(): void
    {
        $response = $this->delete(uri: '/api/filiale/delete', data: [
            'bagush' => 'ghish'
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_UNPROCESSABLE_CONTENT)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }
}

