<?php declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\RequestService;
use App\Tests\APITestClient;
use Symfony\Component\HttpFoundation\Request;

class RequestServiceTest extends APITestClient
{
    /**
     * @var RequestService
     */
    private $requestService;

    /**
     * @var Request
     */
    private $request;

    public function setUp()
    {
        $data = $this->getJsonFixture('QuestionRequestPOST');
        $this->request = new Request([], [], [], [], [], [], $data);
        $this->requestService = new RequestService();
    }

    public function testGetData()
    {
        $returnedData = $this->requestService->getData($this->request);
        $this->assertArrayNotHasKey('data', $returnedData);
    }
}