<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Response;

/**
 * Class SendSMSResponse
 *
 * @package GlobeGroup\NotifierBundle\Response
 */
class SendSMSResponse
{
    private $meta;

    private $data;

    private $errors;

    public function __construct(array $response)
    {
        $this->meta = new Meta($response['meta']);

        foreach ($response['data'] ?? [] as $datum) {
            $this->data[] = new Datum($datum);
        }

        foreach ($response['errors'] ?? [] as $error) {
            $this->errors[] = new Error($error);
        }
    }

    public function getMeta(): Meta
    {
        return $this->meta;
    }

    /**
     * @return Datum[]|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @return \Error[]|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->getErrors() ?? []) !== 0;
    }
}
