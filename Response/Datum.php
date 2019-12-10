<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Response;

/**
 * Class Datum
 *
 * @package GlobeGroup\NotifierBundle\Response
 */
class Datum
{
    private $id;

    private $number;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->number = $data['number'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNumber()
    {
        return $this->number;
    }
}
