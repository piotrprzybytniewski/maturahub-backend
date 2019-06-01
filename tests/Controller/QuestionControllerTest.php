<?php declare(strict_types=1);


namespace App\Tests\Controller;


use App\Tests\APITestClient;

class QuestionControllerTest extends APITestClient
{

    public function testGETSingleQuestion()
    {
        $token = $this->getAuthToken();
        print_r($token);
        $this->client->request(
            'GET',
            '/api/questions/2',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $token
            ]
        );
        $this->assertResponse($this->client->getResponse(), 200);
    }
}