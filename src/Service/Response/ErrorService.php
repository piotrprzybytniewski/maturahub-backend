<?php declare(strict_types=1);


namespace App\Service\Response;


use Symfony\Component\Validator\ConstraintViolationListInterface;

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

    public function setValidationError(ConstraintViolationListInterface $errors, int $i): array
    {
        $errorsArray = [];
        foreach ($errors as $error) {
            $errorsArray[][$error->getPropertyPath()] = [
                "message" => $error->getMessage(),
                "index" => $i
            ];
        }

        $errorResponse = $this->setError(422, "Validation failed");
        $errorResponse = $this->addErrorsArray($errorsArray, $errorResponse);

        return $errorResponse;
    }

    public function addErrorsArray(array $errorsArray, array $errorResponse): array
    {
        $errorResponse["error"]["errors"] = $errorsArray;

        return $errorResponse;
    }
}