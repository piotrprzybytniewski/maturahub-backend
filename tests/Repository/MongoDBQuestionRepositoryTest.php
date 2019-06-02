<?php declare(strict_types=1);


namespace App\Tests\Repository;


use App\Repository\Question\MongoDBQuestionRepository;
use App\Tests\APITestClient;

class MongoDBQuestionRepositoryTest extends APITestClient
{
    /**
     * @var MongoDBQuestionRepository
     */
    private $questionRepository;

    protected function setUp()
    {
        $this->questionRepository = new MongoDBQuestionRepository();
    }

    /**
     * @dataProvider limitProvider
     */
    public function testFindRandom($limit)
    {
        $questions = $this->questionRepository->findRandom($limit);

        $this->assertCount($limit, $questions);
    }

    /**
     * @dataProvider valuesBeyondTheLimit
     */
    public function testIfFindRandomReturnsOneQuestionForBeyondTheLimitValues($limit)
    {
        $questions = $this->questionRepository->findRandom($limit);
        $this->assertCount(1, $questions);
    }


    public function limitProvider(): array
    {
        return [
            [1],
            [2],
            [3],
        ];
    }

    public function valuesBeyondTheLimit(): array
    {
        return [
            [21],
            [2999],
            [214512512512]
        ];
    }
}