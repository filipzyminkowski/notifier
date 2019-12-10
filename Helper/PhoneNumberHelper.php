<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Helper;

/**
 * Class PhoneNumberHelper
 *
 * @package GlobeGroup\NotifierBundle\Helper
 */
class PhoneNumberHelper
{
    public static function formatArrayForEmailLabs(array $phoneNumbers): array
    {
        $formattedNumbers = [];
        foreach ($phoneNumbers as $phoneNumber) {
            $formattedNumbers[] = self::formatOneForEmailLabs($phoneNumber);
        }

        return $formattedNumbers;
    }

    public static function formatOneForEmailLabs(string $phoneNumber): string
    {
        $phoneNumber = str_replace([' ', '-', '(', ')'], '', $phoneNumber);
        $phoneNumber = trim($phoneNumber);

        return '+48' . substr($phoneNumber, -9);
    }
}
