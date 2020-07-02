<?php

namespace App\Tests\Controller\Organization;

use App\Tests\TokenWebTestCase;

/**
 * Тесты редактирования организации
 *
 * @author Stas Lozitskiy
 */
class ActEditOrganizationTest extends TokenWebTestCase
{
    public function testChangeNameSuccess()
    {
        $client = $this->createAuthenticatedClient();

        $newName = 'ООО Новое Название';
        $client->request(
            'PUT',
            '/api/organization',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => $newName])
        );

        $response = $client->getResponse();

        $this->assertEquals( json_encode(['result' => 'success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        $origOrganization = $this->getTestOrganization('findOneBy', ['name' => $newName]);
        $this->assertNotNull($origOrganization);
    }

    public function testChangeNameError()
    {
        $client = $this->createAuthenticatedClient();

        $newName = '';
        $client->request(
            'PUT',
            '/api/organization',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => $newName])
        );

        $response = $client->getResponse();

        $this->assertEquals( json_encode(['result' => 'ERR_COMMON']), $response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
    }
}
