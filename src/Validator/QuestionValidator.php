<?php


namespace App\Validator;

use App\DataTransformer\JSONToQuestionObjectTransformer;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionValidator
{
    private $validator;
    private $JSONToQuestionObjectTransformer;

    public function __construct(ValidatorInterface $validator, JSONToQuestionObjectTransformer $JSONToQuestionObjectTransformer)
    {
        $this->validator = $validator;
        $this->JSONToQuestionObjectTransformer = $JSONToQuestionObjectTransformer;
    }

    public function validateOne($question)
    {
            $errors = $this->validator->validate($question);
            if (count($errors) > 0) {
                throw new UnprocessableEntityHttpException();
            }
    }

    public function validate(array $questions)
    {
        foreach ($questions as $question) {
                $questionObject = $this->JSONToQuestionObjectTransformer->transform($question);
                $this->validateOne($questionObject);
        }
    }
}