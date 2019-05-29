<?php declare(strict_types=1);


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RequestService
{
    public function getData(Request $request): ?array
    {
        $requestData = json_decode($request->getContent(), true);
        return $requestData['data'];
    }
}