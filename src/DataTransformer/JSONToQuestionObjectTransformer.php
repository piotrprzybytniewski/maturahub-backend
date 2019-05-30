<?php


namespace App\DataTransformer;

use App\Document\Question;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class JSONToQuestionObjectTransformer
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function transform($question)
    {
        $question = json_encode($question);
        try {
            $question = $this->serializer->deserialize(
                $question,
                Question::class,
                'json',
                ['allow_extra_fields' => false]
            );
        } catch (\Throwable $e) {
            throw new UnprocessableEntityHttpException();
        }

        return $question;
    }


}