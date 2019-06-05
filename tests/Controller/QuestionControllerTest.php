<?php declare(strict_types=1);


namespace App\Tests\Controller;


use App\Tests\APITestClient;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class QuestionControllerTest extends APITestClient
{

    public function requestForSpecifiedLimit($limit, string $method = 'GET', string $content = '')
    {

        $this->client->request(
            $method,
            '/api/questions'.($limit ? '/'.$limit : ''),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            $content
        );
    }

    /**
     * Canonicalize for asertingEqual in random order of array keys
     *
     * @dataProvider correctLimitsProvider
     */
    public function testGETForCorrectLimitReturnsSuccess($limit)
    {
        $this->requestForSpecifiedLimit($limit);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 200);
        $this->assertArrayHasKey('data', $this->decode($response->getContent()));
        $firstQuestion = $this->getData($response->getContent())[0];
        $this->assertArrayHasKey('question', $this->getData($response->getContent())[0]);
        $this->assertEquals(
            [
                '_id',
                'subject',
                'level',
                'section',
                'source',
                'year',
                'question',
                'answer',
            ],
            array_keys($firstQuestion),
            "\$canonicalize = true",
            0.0,
            10,
            true
        );
    }


    /**
     * @dataProvider incorrectLimitsProvider
     */
    public function testGETForIncorrectLimitReturnsNotFoundException($limit)
    {
        $this->requestForSpecifiedLimit($limit);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 404);
        $this->assertArrayHasKey('error', $this->decode($response->getContent()));
    }

    public function testPOSTReturnsInsertedIDsOnSuccess()
    {
        $questions = $this->getJsonFixture('QuestionsRequestPOST');
        $this->requestForSpecifiedLimit('', 'POST', $questions);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 200);
        $returnedIds = $this->getData($response->getContent());
        $this->assertArrayHasKey('data', $this->decode($response->getContent()));
        $this->assertInternalType('array', $returnedIds);
        $this->assertCount(count($this->getData($questions)), $returnedIds);
    }

    public function testPOSTThrowsBadRequestExceptionForNoData()
    {
        $this->requestForSpecifiedLimit('', 'POST', '{"data": []}');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 400);
        $this->assertArrayHasKey('error', $this->decode($response->getContent()));
    }

    public function testPOSTThrowsUnprocessableEntityExceptionForIncorrectData()
    {
        $this->requestForSpecifiedLimit('', 'POST', '{"data": [{}]}');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 422);
        $this->assertArrayHasKey('error', $this->decode($response->getContent()));
    }

    public function correctLimitsProvider(): array
    {
        return [
            [1],
            [2],
            [3],
            [20],
        ];
    }

    public function incorrectLimitsProvider(): array
    {
        return [
            [-5.2],
            [-1],
            [-51],
            ['lorem ipsum'],
            [1248125481],
        ];
    }
}