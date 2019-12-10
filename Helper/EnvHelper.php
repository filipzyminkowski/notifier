<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Helper;

/**
 * Class EnvHelper
 *
 * @package GlobeGroup\NotifierBundle\Helper
 */
class EnvHelper
{
    public static function validateEnvs(array $envs): void
    {
        foreach ($envs as $env) {
            self::validateEnv($env);
        }
    }

    public static function validateEnv(string $env): void
    {
        if (getenv($env) === false) {
            throw new \InvalidArgumentException(sprintf('No "%s" env defined!', $env));
        }
    }
}
