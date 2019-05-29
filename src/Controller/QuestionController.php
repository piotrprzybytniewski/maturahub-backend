<?php declare(strict_types=1);


namespace App\Controller;

use App\DataTransformer\JSONToQuestionObjectTransformer;
use App\Document\Question;
use App\Document\User;
use App\Repository\Question\MongoDBRepository;
use App\Repository\UserRepository;
use App\Service\Database\QuestionService;
use App\Service\RequestService;
use App\Service\Response\ErrorService;
use App\Service\Response\SuccessService;
use App\Validator\QuestionValidator;
use Doctrine\ODM\MongoDB\DocumentManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionController extends AbstractController
{
    /**
     * Returns a certain number of random questions depending on the limit parameter
     *
     * You need to provide limit parameter to get specified number of questions in response
     *
     * @Route("/api/questions/{limit<^[1-9]\d*>?1}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the specified number of random questions depending on the limit",
     *     @SWG\Schema(
     *          @SWG\Property(property="data", type="array", @SWG\Items(ref=@Model(type=Question::class)))
     *     )
     * )
     * @SWG\Response(
     *     response="404",
     *     description="error when data not found",
     *     @SWG\Schema(
     *       @SWG\Property(property="error",type="object",
     *           @SWG\Property(property="code",type="integer", example=404,),
     *               @SWG\Property(property="message", type="string", example="data not found")
     *           )
     *       )
     *     )
     * )
     * @SWG\Parameter(name="limit", type="integer", in="path", default="1")
     * @SWG\Tag(name="Questions")
     */
    public function getQuestion(
        int $limit,
        MongoDBRepository $questionRepository,
        SuccessService $successResponse,
        ErrorService $errorResponse,
        QuestionService $questionService
    ): JsonResponse {

        $questions = $questionService->findRandom($limit);

        return new JsonResponse($successResponse->setData($questions));
    }

    /**
     * @Route("/api/questions", methods={"POST"})
     *     @SWG\Parameter(
     *          name="questions",
     *          in="body",
     *          type="json",
     *          description="Questions data",
     *          required=true,
     * @Model(type=Question::class)
     *
     *     ),
     * @SWG\Response(
     *     response=200,
     *     description="Insert one or more questions to database",
     *         @SWG\Schema(
     *              @SWG\Property(property="data", type="array", @SWG\Items(@SWG\Property(property="$oid", type="string")))
     *         )
     * ),
     * @SWG\Response(
     *     response="400",
     *     description="Incorect syntax of entity",
     *     @SWG\Schema(
     *         @SWG\Property(property="error", type="object",
     *             @SWG\Property(property="code", type="integer", example=400),
     *             @SWG\Property(property="message", type="string", example="Incorect syntax of entity")
     *         )
     *     )
     * )
     * @SWG\Tag(name="Questions")
     */
    public function postQuestion(
        Request $request,
        MongoDBRepository $questionRepository,
        RequestService $requestService,
        ErrorService $errorResponse,
        SuccessService $successResponse,
        QuestionValidator $questionValidator
    ): JsonResponse {
        $questions = $requestService->getData($request);
        try {
            $questionValidator->validateQuestions($questions);
        } catch (\Throwable $t) {
            return new JsonResponse($errorResponse->setError(400, "Incorrect syntax of entity"), 400);
        }

        $insertedIds = $questionRepository->insert($questions);

        return new JsonResponse($successResponse->setData($insertedIds), 200);
    }


}