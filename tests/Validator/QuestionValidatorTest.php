<?php declare(strict_types=1);

namespace App\Tests\Validator;

use App\DataTransformer\JSONToQuestionObjectTransformer;
use App\Document\Answer;
use App\Document\Question;
use App\Tests\APITestClient;
use App\Validator\QuestionValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionValidatorTest extends APITestClient
{

    /**
     * @var ValidatorInterface
     */
    private $symfonyValidator;

    /**
     * @var JSONToQuestionObjectTransformer
     */
    private $JSONToQuestionObjectTransformer;

    /**
     * @var QuestionValidator
     */
    private $questionValidator;

    /**
     * @var array
     */
    private $error;

    public function setUp()
    {
        $this->error = [];
        $this->symfonyValidator = $this->createMock(ValidatorInterface::class);
        $this->JSONToQuestionObjectTransformer = $this->createMock(JSONToQuestionObjectTransformer::class);
        $this->questionValidator = new QuestionValidator(
            $this->symfonyValidator,
            $this->JSONToQuestionObjectTransformer
        );
    }

    public function testValidateOne()
    {
        $question = $this->getValidQuestion();
        $this->symfonyValidator->method('validate')->willReturn($this->error);
        $this->symfonyValidator->expects($this->once())->method('validate')->with($question);
        $this->questionValidator->validateOne($question);

    }

    public function testIfValidateOneThrowsUnprocessableEntityException()
    {
        $question = $this->getInvalidQuestion();
        $this->error = ['error'];
        $this->symfonyValidator->method('validate')->willReturn($this->error);
        $this->symfonyValidator->expects($this->once())->method('validate')->with($question);

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->questionValidator->validateOne($question);
    }

    public function testValidateForArrayOfQuestions()
    {
        $questions = $this->getData($this->getJsonFixture('QuestionsRequestPOST'));
        $this->symfonyValidator->method('validate')->willReturn($this->error);
        $this->symfonyValidator->expects($this->exactly(count($questions)))->method('validate');
        $this->JSONToQuestionObjectTransformer->expects($this->exactly(count($questions)))->method('transform');
        $this->questionValidator->validate($questions);
    }
    
    private function getValidQuestion(): Question
    {
        $question = new Question();
        $answer = $this->createMock(Answer::class);
        $question->setQuestion('test');
        $question->setAnswer($answer);
        $question->setLevel('test');
        $question->setSection('test');
        $question->setSource('test');
        $question->setYear(2019);

        return $question;
    }

    private function getInvalidQuestion(): Question
    {
        $question = new Question();
        $question->setQuestion('question with missing fields');

        return $question;
    }
}