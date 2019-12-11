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

    private $notifierCacheDir;

    public function __construct(SMSLabs $SMSLabs, EmailLabs $emailLabs, string $cacheDir, string $notifierCacheDir)
    {
        $this->SMSLabs = $SMSLabs;
        $this->emailLabs = $emailLabs;

        $this->notifierCacheDir = $cacheDir . DIRECTORY_SEPARATOR . $notifierCacheDir;
        if (!file_exists($this->notifierCacheDir) && !mkdir($concurrentDirectory = $this->notifierCacheDir)
            && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
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
        $file = $this->notifierCacheDir . DIRECTORY_SEPARATOR . $notifierException->getIdentifier();
        $data = 'Data pierwszego wystąpienia błędu: ' . date('Y-m-d H:i:s') . "\n";
        $data .= 'Identyfikator błędu: ' . $notifierException->getIdentifier() . "\n";
        $data .= 'Klasa błędu: ' . get_class($notifierException) . "\n";
        $data .= 'Treść błędu: ' . $notifierException->getMessage() . "\n";
        $data .= 'Kod błędu: ' . $notifierException->getCode() . "\n";
        $data .= 'Plik: ' . $notifierException->getFile() . "\n";
        $data .= 'Linia: ' . $notifierException->getLine() . "\n";
        $data .= 'Ścieżka błędu: ' . "\n" . $notifierException->getTraceAsString() . "\n";

        if (!file_exists($file)) {
            file_put_contents($file, $data);

            return true;
        }

        return false;
    }

    public function checkExceptionSend(NotifierException $notifierException): bool
    {
        $file = $this->notifierCacheDir . DIRECTORY_SEPARATOR . $notifierException->getIdentifier();

        if (!file_exists($file)) {
            return false;
        }

        return true;
    }

    public function uncheckExceptionSend(string $identifier): bool
    {
        $file = $this->notifierCacheDir . DIRECTORY_SEPARATOR . $identifier;

        if (!file_exists($file)) {
            return false;
        }

        return unlink($file);
    }
}
