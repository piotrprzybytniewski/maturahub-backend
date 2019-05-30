<?php declare(strict_types=1);


namespace App\Service\Response;

class ErrorService
{
    public function setError(int $code, string $message): array
    {
        $response = [
            "error" => [
                "code" => $code,
                "message" => $message
            ]
        ];
        return $response;
    }

    public function addErrorsArray(array $errorsArray, array $errorResponse): array
    {
        $errorResponse["error"]["errors"] = $errorsArray;

        return $errorResponse;
    }
}