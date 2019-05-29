<?php declare(strict_types=1);


namespace App\Repository\Question;


use App\Service\Response\ErrorService;
use MongoDB\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

class MongoDBRepository implements RepositoryInterface
{
    private $collection;
    private $errorResponse;

    public function __construct(ErrorService $errorResponse)
    {
        $this->collection = (new Client())->matura->question;
        $this->errorResponse = $errorResponse;
    }

    public function findRandom(int $limit)
    {
        try {
            $results = $this->collection->aggregate(
                [
                    ['$sample' => ['size' => $limit]],
                    ['$limit' => $limit],
                ]
            );

            $questions = [];
            foreach ($results as $result) {
                $questions[] = $result;
            }
        } catch (\Exception $e) {
            return new JsonResponse($this->errorResponse->setError(404, 'Data not found'));
        }

        return $questions;
    }

    public function insert(array $questions)
    {
        try {
            $questionsInserted = $this->collection->insertMany($questions);
        } catch (\Exception $e) {
            return new JsonResponse($this->errorResponse->setError(400, 'An error occurred while insert'));
        }

        return $questionsInserted->getInsertedIds();
    }
}