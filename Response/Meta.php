<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Response;

/**
 * Class Meta
 *
 * @package GlobeGroup\NotifierBundle\Response
 */
class Meta
{
    private $reqId;

    private $numberOfElements;

    private $numberOfErrors;

    public function __construct(array $meta)
    {
        $this->reqId = $meta['req_id'];
        $this->numberOfElements = $meta['number_of_elements'];
        $this->numberOfErrors = $meta['number_of_errors'];
    }

    public function getReqId()
    {
        return $this->reqId;
    }

    public function getNumberOfElements()
    {
        return $this->numberOfElements;
    }

    public function getNumberOfErrors()
    {
        return $this->numberOfErrors;
    }
}
