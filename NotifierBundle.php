<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle;

use GlobeGroup\NotifierBundle\Extension\NotifierExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class NotifierBundle
 */
class NotifierBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new NotifierExtension();
    }
}
