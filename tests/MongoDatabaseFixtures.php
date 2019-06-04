<?php declare(strict_types=1);


namespace App\Tests;


use App\Repository\Question\MongoDBQuestionRepository;

class MongoDatabaseFixtures
{
    /**
     * @var MongoDBQuestionRepository
     */
    private $questionRepository;

    public function __construct()
    {
        $this->questionRepository = new MongoDBQuestionRepository();
    }

    public function loadAll() {
        $this->loadQuestions();
    }

    public function loadQuestions()
    {
        $questions = $this->getData($this->getJsonFixture('QuestionsRequestPOST'));
        $this->questionRepository->insert($questions);
    }

    public function deleteAll()
    {
        $this->deleteQuestions();
    }

    public function deleteQuestions() {
        $this->questionRepository->deleteAll();
    }

    protected function decode(string $responseBody): array
    {
        return json_decode($responseBody, true);
    }

    protected function getData(string $responseBody): array
    {
        return $this->decode($responseBody)['data'];
    }

    protected function getJsonFixture($filename)
    {
        return file_get_contents('%kernel.root_dir%/../tests/Resources/Fixtures/'.$filename.'.json');
    }
}