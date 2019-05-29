<?php declare(strict_types=1);


namespace App\Service\Database;


use App\Repository\Question\RepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class QuestionService
{
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findRandom(int $limit)
    {
        $questions = $this->repository->findRandom($limit);

        return $questions;
    }

    public function insert(array $questions)
    {
        $insertedIds = $this->repository->insert($questions);

        return $insertedIds;
    }

}