<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Sender;

use GlobeGroup\NotifierBundle\Exception\NotifierException;
use GlobeGroup\NotifierBundle\Helper\EnvHelper;
use GlobeGroup\NotifierBundle\Helper\PhoneNumberHelper;
use GlobeGroup\NotifierBundle\Response\SendSMSResponse;

/**
 * Class SMSLabs
 *
 * @package GlobeGroup\NotifierBundle\Sender
 */
class SMSLabs
{
    private const BASE_API_URL = 'https://api.smslabs.net.pl/v2/apiSms/';
    private const SEND_SMS_ACTION = 'sendSms';
    private const SEND_SMS_METHOD = 'POST';

    private $appKey;

    private $secretKey;

    private $senderId;

    private $receivers;

    private $projectName;

    public function __construct()
    {
        $this->validateEnvs();
        $this->assignEnvs();
    }

    public function sendNotification(NotifierException $notifierException): SendSMSResponse
    {
        $curl = curl_init();

        $url = $this->getUrlForAction(self::SEND_SMS_ACTION);

        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->prepareData($notifierException));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::SEND_SMS_METHOD);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "{$this->appKey}:{$this->secretKey}");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        return new SendSMSResponse(json_decode($result, true, 512, JSON_THROW_ON_ERROR));
    }

    private function prepareData(NotifierException $notifierException): string
    {
        $data = [
            'flash' => '0',
            'expiration' => '0',
            'phone_number' => PhoneNumberHelper::formatArrayForEmailLabs($this->receivers),
            'sender_id' => $this->senderId,
            'no_polish_signs' => '1',
            'message' => $this->prepareMessage($notifierException),
        ];

        return http_build_query($data);
    }

    private function prepareMessage(NotifierException $notifierException): string
    {
        return 'W projekcie ' . $this->projectName . ' wystąpił błąd. '
            . 'Komunikat: ' . $notifierException->getMessage() . '.'
            . 'Identyfikator: ' . $notifierException->getIdentifier()
            . '. Szczegóły zostały wysłane na email.';
    }

    private function getUrlForAction(string $action): string
    {
        return self::BASE_API_URL . $action;
    }

    private function validateEnvs(): void
    {
        EnvHelper::validateEnvs([
            'SMS_NOTIFIER_APP_KEY',
            'SMS_NOTIFIER_SECRET_KEY',
            'SMS_NOTIFIER_SENDER_ID',
            'SMS_NOTIFIER_RECEIVERS',
            'SMS_NOTIFIER_PROJECT_NAME',
        ]);
    }

    private function assignEnvs(): void
    {
        $this->appKey = getenv('SMS_NOTIFIER_APP_KEY');
        $this->secretKey = getenv('SMS_NOTIFIER_SECRET_KEY');
        $this->senderId = getenv('SMS_NOTIFIER_SENDER_ID');
        $this->projectName = getenv('SMS_NOTIFIER_PROJECT_NAME');

        $receivers = getenv('SMS_NOTIFIER_RECEIVERS');
        $this->receivers = json_decode($receivers);
    }
}
