<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Exception;

/**
 * Class NotifierException
 */
class NotifierException extends \Exception
{
    public function getIdentifier(): string
    {
        return substr(md5($this->getIdentifierBase()), -10);
    }

    private function getIdentifierBase(): string
    {
        return $this->getTraceAsString() . $this->getFile() . $this->getLine() . $this->getMessage() . $this->getCode();
    }

    public function getTraceForMail(): string
    {
        return str_replace("\n", '<br>', $this->getTraceAsString());
    }
}
