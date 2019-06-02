<?php declare(strict_types=1);


namespace App\Tests\Controller;


use App\Tests\APITestClient;

class QuestionControllerTest extends APITestClient
{

    public function requestForSpecifiedLimit($limit)
    {
        $this->client->request(
            'GET',
            '/api/questions/'.$limit,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );
    }

    /**
     * Canonicalize for asertingEqual in random order of array keys
     *
     * @dataProvider correctLimitsProvider
     */
    public function testCorrectLimitReturnsSuccess($limit)
    {
        $this->requestForSpecifiedLimit($limit);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 200);
        $this->assertArrayHasKey('data', $this->decode($response->getContent()));
        $firstQuestion = $this->getData($response->getContent())[0];
        $this->assertArrayHasKey('question', $this->getData($response->getContent())[0]);
        $this->assertEquals([
            '_id',
            'subject',
            'level',
            'section',
            'source',
            'year',
            'question',
            'answer'
        ], array_keys($firstQuestion), "\$canonicalize = true", 0.0, 10, true);
    }


    /**
    * @dataProvider incorrectLimitsProvider
     */
    public function testIncorrectLimitReturnsNotFoundException($limit)
    {
        $this->requestForSpecifiedLimit($limit);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 404);
        $this->assertArrayHasKey('error', $this->decode($response->getContent()));
    }

    public function correctLimitsProvider(): array
    {
        return [
            [1],
            [2],
            [3],
            [20]
        ];
    }

    public function incorrectLimitsProvider(): array
    {
        return [
            [0],
            [-1],
            [-51],
            ['lorem ipsum'],
            [1248125481]
        ];    
    }
}