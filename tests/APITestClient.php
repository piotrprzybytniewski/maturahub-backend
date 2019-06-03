<?php declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class APITestClient extends WebTestCase
{
    /**
     * @var Client|self
     */
    protected $client;

    /**
     * @before
     */
    protected function setUpClient(): void
    {
        $this->client = static::createClient(array(), array('HTTP_ACCEPT' => 'application/json'));
    }

    protected function tearDown(): void
    {
        $this->client = null;
        parent::tearDown();
    }

    protected function getJsonFixture($filename)
    {
        return file_get_contents('%kernel.root_dir%/../tests/Resources/Fixtures/'.$filename.'.json');
    }

    protected function assertResponse(Response $response, $code = 200)
    {
        $this->assertResponseCode($response, $code);
        $this->assertJsonHeader($response);
    }

    protected function assertJsonHeader(Response $response): void
    {
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    protected function assertResponseCode(Response $response, $code): void
    {
        $this->assertEquals(
            $code,
            $response->getStatusCode()
        );
    }


    protected function authenticateUser(): Response
    {
        $this->client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"username": "test","password": "test"}'
        );
        $response = $this->client->getResponse();

        return $response;
    }

    protected function getAuthToken(): string
    {
        $authResponse = $this->authenticateUser();
        $token = $this->decode($authResponse->getContent())['token'];

        return $token;
    }

    protected function decode(string $responseBody): array
    {
        return json_decode($responseBody, true);
    }

    protected function getData(string $responseBody): array
    {
        return $this->decode($responseBody)['data'];
    }
}