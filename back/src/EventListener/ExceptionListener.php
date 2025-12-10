<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener implements EventSubscriberInterface
{
    public function __construct(private string $environment) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        // Customize your response object to display the exception details
        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $content = [
            'message' => $exception->getMessage(),
        ];

        if (
            $exception instanceof UnprocessableEntityHttpException
            && $exception->getPrevious() instanceof ValidationFailedException
        ) {
            /** @var ValidationFailedException */
            $exception = $exception->getPrevious();
            $violations = $exception->getViolations();
            $content = [
                'message' => $violations[0]->getMessage(),
                'errors' => [],
            ];
            foreach ($violations as $violation) {
                $content['errors'][$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }

        if (in_array($this->environment, ['dev', 'test'])) {
            $content = [
                ...$content,
                "exception" => get_class($exception),
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "trace" => array_map(fn($item) => ["file" => $item['file'] ?? null, "line" => $item['line'] ?? null], $exception->getTrace()),
            ];
        }

        $response->setData($content);

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
