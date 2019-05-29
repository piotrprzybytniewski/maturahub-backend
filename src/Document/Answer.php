<?php declare(strict_types=1);


namespace App\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;

class Answer
{
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
    private $a;

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
    private $b;

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
    private $c;

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
    private $d;

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
    private $correct;

    public function getA(): ?string
    {
        return $this->a;
    }

    public function setA(string $a): void
    {
        $this->a = $a;
    }

    public function getB(): ?string
    {
        return $this->b;
    }

    public function setB(string $b): void
    {
        $this->b = $b;
    }

    public function getC(): ?string
    {
        return $this->c;
    }

    public function setC(string $c): void
    {
        $this->c = $c;
    }

    public function getD(): ?string
    {
        return $this->d;
    }

    public function setD(string $d): void
    {
        $this->d = $d;
    }


    public function getCorrect(): ?string
    {
        return $this->correct;
    }

    public function setCorrect(string $correct): void
    {
        $this->correct = $correct;
    }


}