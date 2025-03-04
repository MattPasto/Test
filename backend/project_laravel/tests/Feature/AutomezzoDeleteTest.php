<?php

namespace Tests\Feature;

use App\Models\Automezzo;
use App\Traits\UtilsTrait;
use Tests\TestCase;

class AutomezzoDeleteTest extends TestCase
{
    use UtilsTrait;
    /**
     * Test per la distruzione di un Automezzo
     * @return void
     */
    public function testDeleteSuccess(): void
    {
        // Prendo un automezzo da sacrificare 
        $automezzo_id = Automezzo::first()->id;

        $response = $this->delete(uri: '/api/automezzo/delete', data: [
            'id' => $automezzo_id
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_OK)->assertJson(value: [
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
        $response = $this->delete(uri: '/api/automezzo/delete', data: [
            'bagush' => 'ghish'
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_UNPROCESSABLE_CONTENT)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }

    /**
     * Test per la verificare il comportamentocon un automezzo inesistente
     * @return void
     */
    public function testDeleteFilialeError(): void
    {
        //Recupero un id che sono sicuro non esista
        $automezzo_id = Automezzo::orderBy('id', 'desc')->first()->id + 1;

        $response = $this->delete(uri: '/api/automezzo/delete', data: [
            'id' => $automezzo_id
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_NOT_FOUND)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }
}
