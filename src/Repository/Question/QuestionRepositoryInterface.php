<?php declare(strict_types=1);


namespace App\Repository\Question;


interface QuestionRepositoryInterface
{
    public function findRandom(int $limit): ?array;

    public function insert(array $questions): ?array;

}