<?php

namespace App\Tests\Controller;

use App\Entity\Department;
use App\Entity\User;
use App\Tests\TokenWebTestCase;

/**
 * Тест контроллера отдела
 */
class DepartmentsControllerTest extends TokenWebTestCase
{
    /**
     * Тест получения списка отделов
     */
    public function testDepartmentsList()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/departments');
        $response = $client->getResponse();

        $departments = [
            'Руководящий состав', 'Отдел технической поддержки', 'Отдел тестирования',
            'Отдел интеграций', 'Отдел разработки'
        ];

        foreach (json_decode($response->getContent(), true) as $index => $item) {
            $this->assertEquals($departments[$index], $item['name']);
        }

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Тест добавления нового отдела
     */
    public function testAddDepartment()
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'POST',
            '/api/departments',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Отдел правды'])
        );
        $response = $client->getResponse();

        $originalDepartment = $this->getTestDepartment('findOneBy', ['name' => 'Отдел правды']);

        $data = ['result' => 'success'];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Отдел правды', $originalDepartment->getName());
        $this->assertEquals( json_encode($data), $response->getContent());
    }

    /**
     * Тест добавление существующего пользователя в отдел
     */
    public function testAddUsers()
    {
        $client = $this->createAuthenticatedClient();

        $originalUser = $this->getTestUser();

        $originalDepartment = $this->getTestDepartment();

        $departmentId = $originalDepartment->getId();

        $usersId = ["users_id" => [ $originalUser->getId(), 0, 12 ]];

        $client->request(
            'PATCH',
            '/api/department/' . $departmentId . '/addusers',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($usersId)
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $department = $this->getTestDepartment('find', $departmentId);

        $user = $this->getTestUser('find', $usersId["users_id"][0]);

        $this->assertSame($user->getEmail(), $department->getUsers()->first()->getEmail());
        $this->assertEquals( json_encode(['result' => 'success']), $response->getContent());
    }

    /**
     * Тест удаления существующего пользователя в отдел
     */
    public function testRemoveUsers()
    {
        $client = $this->createAuthenticatedClient();

        $originalUser = $this->getTestUser();

        $originalDepartment = $this->getTestDepartment();

        $departmentId = $originalDepartment->getId();
        $usersId = ["users_id" => [ $originalUser->getId(), 0, 12 ]];

        $client->request(
            'PATCH',
            '/api/department/' . $departmentId . '/removeusers',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($usersId)
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $department = $this->getTestDepartment('find', $departmentId);
        $this->assertSame(true, $department->getUsers()->isEmpty());
        $this->assertEquals( json_encode(['result' => 'success']), $response->getContent());
    }

    /**
     * Тест удаления отдела
     */
    public function testRemoveDepartment()
    {
        $client = $this->createAuthenticatedClient();

        $originalDepartment = $this->getTestDepartment();

        $departmentId = $originalDepartment->getId();

        $client->request('DELETE', '/api/department/' . $departmentId);

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $department = $this->getTestDepartment('find', $departmentId);

        $this->assertEquals(null, $department);
        $this->assertEquals( json_encode(['result' => 'success']), $response->getContent());
    }

    /**
     * Тест редактирования отдела
     */
    public function testEditDepartment()
    {
        $client = $this->createAuthenticatedClient();

        $originalDepartment = $this->getTestDepartment();
        $masterUser = $this->getTestUser();

        $departmentId = $originalDepartment->getId();

        $data = [
            'name' => 'New name',
            'id' => $departmentId,
            'master_user_id' => $masterUser->getId()
        ];

        $client->request(
            'PUT',
            '/api/department',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $department = $this->getTestDepartment('find', $departmentId);

        $this->assertEquals($data['name'], $department->getName());
        $this->assertEquals($masterUser->getId(), $department->getMasterUser()->getId());
        $this->assertEquals(json_encode(['result' => 'success']), $response->getContent());
    }
}
