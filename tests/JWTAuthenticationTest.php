<?php declare(strict_types=1);


namespace App\Tests;


class JWTAuthenticationTest extends APITestClient
{

    public function testUserAuthentication()
    {
        $authResponse = $this->authenticateUser();
        $this->assertResponse($authResponse, 200);
        $this->assertContains('token', $authResponse->getContent());
    }

    public function testGetAuthToken()
    {
        $token = $this->getAuthToken();
        $this->assertInternalType("string", $token);
    }
}