<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Response;

/**
 * Class Error
 *
 * @package GlobeGroup\NotifierBundle\Response
 */
class Error
{
    private $title;

    private $details;

    public function __construct(array $error)
    {
        $this->title = $error['title'];
        $this->details = $error['details'];
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
