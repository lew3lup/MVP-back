<?php

namespace App\Controller;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DomainExceptionFormatter implements EventSubscriberInterface
{
    /** @var ErrorHandler */
    private $errors;

    /**
     * DomainExceptionFormatter constructor.
     * @param ErrorHandler $errors
     */
    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof \DomainException) {
            return;
        }

        $this->errors->handle($exception);

        $event->allowCustomResponseCode();

        $responseStatus = $exception->getCode() ? $exception->getCode() : 400;
        $responseBody = [
            'type'  => 'error',
            'error' => [
                'message' => $exception->getMessage(),
            ],
        ];

        $response = new JsonResponse($responseBody, $responseStatus);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $event->setResponse($response);
    }
}
