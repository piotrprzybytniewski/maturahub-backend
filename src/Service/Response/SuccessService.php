<?php declare(strict_types=1);


namespace App\Service\Response;


class SuccessService
{
    public function setData(array $content): array
    {
        $response = [
            "data" => $content
        ];
        return $response;
    }


}