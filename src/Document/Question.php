<?php declare(strict_types=1);


namespace App\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @SWG\Schema()
 */
class Question
{

    /**
     * @var string
     * @SWG\Property(property="_id", type="object", @SWG\Property(property="$oid", type="string"), readOnly=true)
     *@Assert\Type("string")
     */
    private $id;

    /**
     * @SWG\Property(type="string")
     * @Assert\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 1000
     * )
     */
    private $subject;

    /**
     * @SWG\Property(type="string")
     * @Assert\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 1000
     * )
     */
    private $level;

    /**
     * @SWG\Property(type="string")
     * @Assert\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 1000
     * )
     */
    private $section;

    /**
     * @SWG\Property(type="string")
     * @Assert\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 1000
     * )
     */
    private $source;

    /**
     * @SWG\Property(type="integer")
     * @Assert\Type("int")
     * @Assert\NotNull
     * @Assert\Length(
     *      min = 1,
     *      max = 1000
     * )
     */
    private $year;

    /**
     * @SWG\Property(type="string")
     * @Assert\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 1000
     * )
     */
    private $question;

    /**
     * @SWG\Property(ref=@Model(type=Answer::class))
     * @Assert\Type("object")
     * @Assert\Valid
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private $answer;

    /**
     * @var string
     * @SWG\Property()
     *@Assert\Type("string")
     */

    private $userId;

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level): void
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     */
    public function setSection($section): void
    {
        $this->section = $section;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source): void
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }


    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(Answer $answer): void
    {
        $this->answer = $answer;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

}