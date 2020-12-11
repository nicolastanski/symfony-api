<?php

namespace App\EventListeners;

use App\Helper\EntityFactoryException;
use App\Helper\ResponseFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleEntityException', 1],
                ['handle404Exception', 0],
                ['handleGenericException', -1]
            ],
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $event->setResponse(new JsonResponse([
                'mensagem' => 'Erro 404'
            ], Response::HTTP_NOT_FOUND));
        }
    }

    public function handleEntityException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof EntityFactoryException) {
            $event->setResponse(new JsonResponse('as', Response::HTTP_BAD_REQUEST));
        }
    }

    public function handleGenericException(ExceptionEvent $event)
    {
        $this->logger->critical(
            'Uma exceção ocorreu. {stack}', 
            ['stack' => $event->getThrowable()->getTraceAsString()]
        );

        $response = new ResponseFactory(false, $event->getThrowable()->getMessage());
        $event->setResponse($response->getResponse());
    }
}