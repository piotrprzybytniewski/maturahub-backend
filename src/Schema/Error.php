<?php declare(strict_types=1);


namespace App\Schema;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @SWG\Schema()
 */
class Error
{
    /**
     * @SWG\Property(format="int32")
     * @var int
     */
    public $code;

    /**
     * @SWG\Property()
     * @var string
     */
    public $message;
}