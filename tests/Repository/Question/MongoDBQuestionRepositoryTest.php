<?php declare(strict_types=1);

namespace App\Tests\Repository\Question;

use App\Repository\Question\MongoDBQuestionRepository;
use App\Tests\APITestClient;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

    public function testInsert()
    {
        $question = $this->getData($this->getJsonFixture('QuestionsRequestPOST'));
        $ids = $this->questionRepository->insert($question);
        $this->assertInternalType("array", $ids);
        $this->assertCount(count($question), $ids);
    }

    public function testInsertThrowsBadRequestException()
    {
        $question = [123];
        $this->expectException(BadRequestHttpException::class);

        $this->questionRepository->insert($question);
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
            [214512512512],
        ];
    }
}