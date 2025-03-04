<?php

namespace Tests\Feature;

use App\Traits\UtilsTrait;
use Tests\TestCase;

class FilialeCreateTest extends TestCase
{
    use UtilsTrait;
    /**
     * Test per la creazione di una Filiale
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $response = $this->post(uri: '/api/filiale/create', data: [
            'codice' => "893120-KALST",
            'indirizzo' => "via San Giuseppe n11",
            'città' => "Ravenna",
            'cap' => "637189"
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
    public function testCreateErrorValidation(): void
    {
        $response = $this->post(uri: '/api/filiale/create', data: [
            'codice' => 12,
            'indirizzo' => "via San Giuseppe n11",
            'città' => "Ravenna",
            'cap' => "637189"
        ]);

        $body = json_decode(json: $response->getContent(), associative: true);

        $response->assertStatus(status: self::HTTP_UNPROCESSABLE_CONTENT)->assertJson(value: [
            "message" => true
        ]);

        $this->assertIsString(actual: $body['message']);
    }
}
