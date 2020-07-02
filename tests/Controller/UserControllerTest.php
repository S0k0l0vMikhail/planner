<?php

namespace App\Tests\Controller;

use App\Entity\Department;
use App\Entity\Organization;
use App\Entity\User;
use App\Tests\TokenWebTestCase;

/**
 * Тест контроллера пользователя
 */
class UserControllerTest extends TokenWebTestCase
{
    /**
     * Тестирует метод получения всех пользователей организации
     */
    public function testUser()
    {
        $client = $this->createAuthenticatedClient();

        $originalUser = $this->getTestUser();

        $client->request('GET', '/api/users');
        $response = $client->getResponse();
        $answerData = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($originalUser->getId(), $answerData[0]['id']);
        $this->assertEquals($originalUser->getUsername(), $answerData[0]['name']);
    }

    /**
     * Тестирует метод получения всех пользователей отдела
     */
    public function testColleagues()
    {
        $client = $this->createAuthenticatedClient();

        $originalUser = $this->getTestUser();

        $originalDepartment = $this->getTestDepartment();

        $client->request('GET', '/api/colleagues/' . $originalDepartment->getId());
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode([$originalUser->getId() => "root"]), $response->getContent());
    }

    /**
     * Тестирует метод добавление пользователей отдела
     */
    public function testAddUser()
    {
        $client = $this->createAuthenticatedClient();

        $user = [
            'name' => 'testUser',
            'email' => 'test@test.com'
        ];

        $client->request(
            'POST',
            '/api/user',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $newUser = $this->getTestUser('findOneBy', ['name' => 'testUser']);

        $this->assertSame('test@test.com', $newUser->getEmail());
        $this->assertEquals(json_encode(['result' => 'success']), $response->getContent());
    }

    /**
     * Тестирует метод добавление пользователей отдела
     */
    public function testAddFirstUser()
    {
        $client = $this->createAuthenticatedClient();

        $user = [
            'name' => 'testUser',
            'email' => 'test@test.com',
            'password' => '111',
            'organization_name' => 'testOrganization'
        ];

        $client->request(
            'POST',
            '/api/user/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $newUser = $this->getTestUser('findOneBy', ['name' => 'testUser']);

        $newOrganization = self::bootKernel()->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Organization::class)
            ->findOneBy(['name' => 'testOrganization']);

        $this->assertSame('test@test.com', $newUser->getEmail());
        $this->assertSame(['ROLE_ADMIN', 'ROLE_USER'], $newUser->getRoles());
        $this->assertSame('testOrganization', $newOrganization->getName());
        $this->assertEquals(json_encode(['result' => 'success']), $response->getContent());
    }

    /**
     * Тестирует метод добавление пользователей отдела с занятым email
     */
    public function testAddFirstUserWithTakenEmail()
    {
        $client = $this->createAuthenticatedClient();

        $user = [
            'name' => 'testUser',
            'email' => 'root@root.root',
            'password' => '111',
            'organization_name' => 'testOrganization'
        ];

        $client->request(
            'POST',
            '/api/user/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($user)
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(json_encode(['result' => 'email already taken']), $response->getContent());
    }

    /**
     * Тестирует метод изменения данных пользователей
     */
    public function testEditUser()
    {
        $client = $this->createAuthenticatedClient();

        $originalUser = $this->getTestUser();

        $dataToChange = [
            'id' => $originalUser->getId(),
            'name' => 'testUser',
            'email' => 'root@root.test'
        ];

        $client->request(
            'PUT',
            '/api/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($dataToChange)
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $editedUser = $this->getTestUser('find', $originalUser->getId());

        $this->assertEquals($dataToChange['email'], $editedUser->getEmail());
        $this->assertEquals($dataToChange['name'], $editedUser->getUsername());
        $this->assertEquals(json_encode(['result' => 'success']), $response->getContent());
    }
}
