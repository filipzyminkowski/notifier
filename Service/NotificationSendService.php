<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Service;

use GlobeGroup\NotifierBundle\Exception\NotifierException;
use GlobeGroup\NotifierBundle\Sender\EmailLabs;
use GlobeGroup\NotifierBundle\Sender\SMSLabs;

/**
 * Class NotificationSendService
 */
class NotificationSendService
{
    private $SMSLabs;

    private $emailLabs;

    private $cacheDir;

    private $notifierCacheDir;

    public function __construct(SMSLabs $SMSLabs, EmailLabs $emailLabs, string $cacheDir, string $notifierCacheDir)
    {
        $this->SMSLabs = $SMSLabs;
        $this->emailLabs = $emailLabs;

        $this->cacheDir = $cacheDir;
        $this->notifierCacheDir = $cacheDir . DIRECTORY_SEPARATOR . $notifierCacheDir . DIRECTORY_SEPARATOR;
    }

    public function sendException(NotifierException $notifierException): void
    {
        if (!$this->checkExceptionSend($notifierException)) {
            $this->SMSLabs->sendNotification($notifierException);
            $this->emailLabs->sendNotification($notifierException);

            $this->markExceptionSend($notifierException);
        }
    }

    public function markExceptionSend(NotifierException $notifierException): bool
    {
        $file = $this->cacheDir . $notifierException->getIdentifier();
        $data = $notifierException->getTraceAsString();

        if (!file_exists($file)) {
            file_put_contents($file, $data);

            return true;
        }

        return false;
    }

    public function checkExceptionSend(NotifierException $notifierException): bool
    {
        $file = $this->notifierCacheDir . $notifierException->getIdentifier();

        if (!file_exists($file)) {
            return false;
        }

        return true;
    }

    public function uncheckExceptionSend(string $identifier): bool
    {
        $file = $this->notifierCacheDir . $identifier;

        if (!file_exists($file)) {
            return false;
        }

        return unlink($file);
    }
}
