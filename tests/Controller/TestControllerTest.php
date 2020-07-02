<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestControllerTest extends WebTestCase
{
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    protected function createAuthenticatedClient($username = 'root@root.root', $password = '000')
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

        $data = json_decode($client->getResponse()->getContent(), true);//print_r($data);exit;
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testTestController()
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/test');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode(['result' => 123]), $response->getContent());
    }
}
