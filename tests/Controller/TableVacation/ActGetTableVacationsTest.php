<?php

namespace App\Tests\Controller\TableVacation;

use App\Tests\TokenWebTestCase;

/**
 * Тест получения графиков
 */
class ActGetTableVacationsTest extends TokenWebTestCase
{
    public function testGetTableVacations()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/tablevacations');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        foreach (json_decode($response->getContent(), true) as $item) {
            $this->assertEquals('График отпусков', $item['name']);
        }
    }
}
