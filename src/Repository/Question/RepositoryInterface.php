<?php declare(strict_types=1);


namespace App\Repository\Question;


interface RepositoryInterface
{
    public function findRandom(int $limit);

    public function insert(array $questions);

}