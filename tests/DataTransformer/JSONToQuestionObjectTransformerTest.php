<?php declare(strict_types=1);


namespace App\Tests\DataTransformer;


use App\DataTransformer\JSONToQuestionObjectTransformer;
use App\Document\Question;
use App\Tests\APITestClient;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class JSONToQuestionObjectTransformerTest extends APITestClient
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var JSONToQuestionObjectTransformer
     */
    private $transformer;

    public function setUp()
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->transformer = new JSONToQuestionObjectTransformer($this->serializer);
    }

    public function testTransformForMultipleQuestions()
    {
        $questions = $this->getData($this->getJsonFixture('QuestionsRequestPOST'));

        $this->serializer->expects(
            $this->exactly(3)
        )->method('deserialize')
            ->with($this->anything(), Question::class, 'json', ['allow_extra_fields' => false]);
        foreach ($questions as $question) {
            $this->transformer->transform($question);
        }
    }

    public function testTransformThrowsUnprocessableEntityException()
    {
        $question = ['incorrect question'];
        $this->serializer->expects($this->exactly(1))
            ->method('deserialize')
            ->with($this->anything(), Question::class, 'json', ['allow_extra_fields' => false]);
        $this->serializer->method('deserialize')->will($this->throwException(new UnprocessableEntityHttpException()));
        $this->expectException(UnprocessableEntityHttpException::class);

        $this->transformer->transform($question);
    }

}