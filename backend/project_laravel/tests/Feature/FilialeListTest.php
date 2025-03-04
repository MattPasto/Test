<?php

namespace Tests\Feature;

use App\Traits\UtilsTrait;
use Tests\TestCase;

class FilialeListTest extends TestCase
{
    use UtilsTrait;
    /**
     * Test per la lista degli Automezzi
     * @return void
     */
    public function testListSuccess(): void
    {
        $response = $this->get(uri: '/api/filiale/list');

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_OK)->assertJson(value: [
            "data" => true
        ]);

        $this->assertIsArray(actual: $body['data']);
        $this->assertIsArray(actual: $body['data'][0]);
        $this->assertIsInt(actual: $body['data'][0]['id']);
        $this->assertIsInt(actual: $body['data'][0]['automezzi_tot']);
        $this->assertIsString(actual: $body['data'][0]['codice']);
        $this->assertIsString(actual: $body['data'][0]['indirizzo']);
        $this->assertIsString(actual: $body['data'][0]['città']);
        $this->assertIsString(actual: $body['data'][0]['cap']);
        $this->assertIsString(actual: $body['data'][0]['created_at']);
        $this->assertIsString(actual: $body['data'][0]['updated_at']);
    }
}
