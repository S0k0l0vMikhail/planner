<?php

namespace App\Tests;

use App\Entity\Department;
use App\Entity\Organization;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Класс для получения токена авторизации. Все тесты должны наследоваться от него.
 */
class TokenWebTestCase extends WebTestCase
{
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    public function createAuthenticatedClient($username = 'root@root.root', $password = '000')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    /**
     * Получение тестового пользователя
     *
     * @param string $method
     * @param mixed $options
     * @return User
     */
    public function getTestUser($method = 'findOneBy', $options = ['email' => 'root@root.root'])
    {
        return self::bootKernel()->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(User::class)
            ->$method($options);
    }

    /**
     * Получение тестового отдела
     *
     * @param string $method
     * @param mixed $options
     * @return Department
     */
    public function getTestDepartment($method = 'findOneBy', $options = ['name' => 'Отдел тестирования'])
    {
        return self::bootKernel()->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Department::class)
            ->$method($options);
    }

    /**
     * Получение тестовой организации
     *
     * @param string $method
     * @param mixed $options
     * @return Organization
     */
    public function getTestOrganization($method = 'findOneBy', $options = ['name' => 'ООО Рога и Копыта'])
    {
        return self::bootKernel()->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository(Organization::class)
            ->$method($options);
    }
}
