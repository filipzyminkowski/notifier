<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Exception;

/**
 * Class NotifierException
 */
class NotifierException extends \Exception
{
    public function getIdentifier(): string
    {
        return md5($this->getTraceAsString());
    }

    public function getTraceForMail(): string
    {
        return str_replace("\n", '<br>', $this->getTraceAsString());
    }
}
