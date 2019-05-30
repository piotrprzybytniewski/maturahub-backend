<?php declare(strict_types=1);


namespace App\Controller;

use App\Document\Question;
use App\Repository\Question\QuestionRepositoryInterface;
use App\Service\Database\QuestionService;
use App\Service\RequestService;
use App\Service\Response\SuccessService;
use App\Validator\QuestionValidator;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * Returns a certain number of random questions depending on the limit parameter (1-20)
     *
     * You need to provide limit parameter in range 1-20 to get specified number of questions in response. Default limit is 1
     *
     * @Route("/api/questions/{limit<^[1-9]|1[0-9]|20>?1}", methods={"GET"})
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
        SuccessService $successResponse,
        QuestionRepositoryInterface $questionRepository
    ): JsonResponse {

        $questions = $questionRepository->findRandom($limit);

        return new JsonResponse($successResponse->setData($questions));
    }

    /**
     * @Route("/api/questions", methods={"POST"})
     * @SWG\Parameter(
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
     *     description="Bad request",
     *     @SWG\Schema(
     *         @SWG\Property(property="error", type="object",
     *             @SWG\Property(property="code", type="integer", example=400),
     *             @SWG\Property(property="message", type="string", example="Bad request")
     *         )
     *     )
     * ),
     * @SWG\Response(
     *     response="422",
     *     description="Returned when request data is incorrect and an error occured while transforming, validating ,inserting it.",
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
        QuestionRepositoryInterface $questionRepository,
        RequestService $requestService,
        SuccessService $successResponse,
        QuestionValidator $questionValidator
    ): JsonResponse {
        $questions = $requestService->getData($request);
        if (!$questions) {
            throw new BadRequestHttpException();
        }

        $questionValidator->validate($questions);

        $insertedIds = $questionRepository->insert($questions);

        return new JsonResponse($successResponse->setData($insertedIds));
    }


}