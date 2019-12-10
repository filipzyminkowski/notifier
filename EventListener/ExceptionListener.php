<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\EventListener;

use GlobeGroup\NotifierBundle\Exception\NotifierException;
use GlobeGroup\NotifierBundle\Service\NotificationSendService;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 */
class ExceptionListener
{
    private $notificationSendService;

    public function __construct(NotificationSendService $notificationSendService)
    {
        $this->notificationSendService = $notificationSendService;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotifierException) {
            $this->notificationSendService->sendException($exception);
        }
    }
}
