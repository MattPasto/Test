<?php

namespace Tests\Feature;

use App\Traits\UtilsTrait;
use Tests\TestCase;

class AutomezzoListTest extends TestCase
{
    use UtilsTrait;
    /**
     * Test per la lista degli Automezzi
     * @return void
     */
    public function testListSuccess(): void
    {
        $response = $this->get(uri: '/api/automezzo/list');

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_OK)->assertJson(value: [
            "data" => true
        ]);

        $this->assertIsArray(actual: $body['data']);
        $this->assertIsArray(actual: $body['data'][0]);
        $this->assertIsInt(actual: $body['data'][0]['id']);
        $this->assertIsInt(actual: $body['data'][0]['filiale_id']);
        $this->assertIsString(actual: $body['data'][0]['codice']);
        $this->assertIsString(actual: $body['data'][0]['targa']);
        $this->assertIsString(actual: $body['data'][0]['marca']);
        $this->assertIsString(actual: $body['data'][0]['modello']);
        $this->assertIsString(actual: $body['data'][0]['created_at']);
        $this->assertIsString(actual: $body['data'][0]['updated_at']);
        $this->assertIsString(actual: $body['data'][0]['filiale_codice']);
    }
}
