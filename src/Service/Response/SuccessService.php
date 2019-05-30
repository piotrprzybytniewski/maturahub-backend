<?php declare(strict_types=1);


namespace App\Service\Response;


class SuccessService
{
    public function setData(array $data): array
    {
        $response = [
            "data" => $data
        ];
        return $response;
    }


}