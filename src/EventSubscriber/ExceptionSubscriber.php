<?php declare(strict_types=1);


namespace App\EventSubscriber;

use App\Service\Response\ErrorService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;


class ExceptionSubscriber implements EventSubscriberInterface
{
    private $errorResponse;

    public function __construct(ErrorService $errorResponse)
    {
        $this->errorResponse = $errorResponse;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array(
                array('processException', 100),
            ),
        );
    }

    public function processException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $response = new JsonResponse();
        $message = $exception->getMessage() ? $exception->getMessage() : null;

        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();
            $response->headers->replace($exception->getHeaders());
            if ($exception instanceof AccessDeniedHttpException) {
                $message = $exception->getMessage();
            }
        } else {
            $status = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        }
        if (!$message) {
            $message = isset(JsonResponse::$statusTexts[$status])
                ? JsonResponse::$statusTexts[$status] : "Unknown error";
        }


        $data = $this->errorResponse->setError($status, $message);

        $response->setData($data);
        $response->setStatusCode($status);
        $event->setResponse($response);
    }
}