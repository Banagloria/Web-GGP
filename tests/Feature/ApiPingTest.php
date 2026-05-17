<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiPingTest extends TestCase
{
    public function test_api_v1_ping_mengembalikan_json_ok(): void
    {
        $response = $this->getJson('/api/v1/ping');

        $response->assertOk()
            ->assertJson(['ok' => true, 'service' => 'web-gereja']);
    }
}
