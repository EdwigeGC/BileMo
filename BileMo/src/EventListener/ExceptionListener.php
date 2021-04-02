<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {

        // Gets the exception object from the received event
        $exception = $event->getThrowable();
        $code = $this->getErrorCode($exception);

        $message= [
            'code'=>$code,
            'message'=>$exception->getMessage()
        ];

        $response = new JsonResponse($message, $code);
        $response->setContent($response);

        $event->setResponse($response);
    }

    public function getErrorCode(\Throwable $exception)
    {
        if (!$exception->getCode() && $exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        } elseif (!$exception->getCode()) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        } else {
            $code = $exception->getCode();
        }
        return $code;
    }
}