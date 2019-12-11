<?php declare(strict_types=1);

namespace GlobeGroup\NotifierBundle\Sender;

use GlobeGroup\NotifierBundle\Exception\NotifierException;
use GlobeGroup\NotifierBundle\Helper\EnvHelper;

/**
 * Class EmailLabs
 *
 * @package GlobeGroup\NotifierBundle\Sender
 */
class EmailLabs
{
    private const BASE_API_URL = 'https://api.emaillabs.net.pl/api/';
    private const SEND_EMAIL_ACTION = 'new_sendmail';
    private const SEND_EMAIL_METHOD = 'POST';

    private $smtpAccount;

    private $appKey;

    private $secretKey;

    private $receivers;

    private $projectName;

    public function __construct()
    {
        $this->validateEnvs();
        $this->assignEnvs();
    }

    public function sendNotification(NotifierException $notifierException)
    {
        $curl = curl_init();

        $url = $this->getUrlForAction(self::SEND_EMAIL_ACTION);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->prepareData($notifierException));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::SEND_EMAIL_METHOD);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "{$this->appKey}:{$this->secretKey}");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }

    private function prepareData(NotifierException $notifierException): string
    {
        $data = [
            'to' => $this->receivers,
            'smtp_account' => $this->smtpAccount,
            'subject' => $this->prepareSubject($notifierException),
            'html' => $this->prepareMessage($notifierException),
            'from' => 'alert@globegroup.pl',
            'from_name' => 'Globe Group Alert',
            'headers' => [
                'x-header-1' => 'test-1',
                'x-header-2' => 'test-2',
            ],
            'tags' => [
                'NOTIFIER',
            ],
        ];

        return http_build_query($data);
    }

    private function prepareSubject(NotifierException $notifierException): string
    {
        return '[ALERT][' . $this->projectName . '] Wystąpił błąd. Zapoznaj się ze szczegółami.';
    }

    private function prepareMessage(NotifierException $notifierException): string
    {
        $content = '<strong>Data pierwszego wystąpienia błędu:</strong> ' . date('Y-m-d H:i:s') . '<br>';
        $content .= '<strong>Identyfikator błędu:</strong> ' . $notifierException->getIdentifier() . '<br>';
        $content .= '<strong>Klasa błędu:</strong> ' . get_class($notifierException) . '<br>';
        $content .= '<strong>Treść błędu:</strong> ' . $notifierException->getMessage() . '<br>';
        $content .= '<strong>Kod błędu:</strong> ' . $notifierException->getCode() . '<br>';
        $content .= '<strong>Plik:</strong> ' . $notifierException->getFile() . '<br>';
        $content .= '<strong>Linia:</strong> ' . $notifierException->getLine() . '<br>';
        $content .= '<strong>Ścieżka błędu:</strong> <br>' . $notifierException->getTraceForMail() . '<br>';

        return $content;
    }

    private function getUrlForAction(string $action): string
    {
        return self::BASE_API_URL . $action;
    }

    private function validateEnvs(): void
    {
        EnvHelper::validateEnvs([
            'EMAIL_NOTIFIER_SMTP_ACCOUNT',
            'EMAIL_NOTIFIER_APP_KEY',
            'EMAIL_NOTIFIER_SECRET_KEY',
            'EMAIL_NOTIFIER_RECEIVERS',
        ]);
    }

    private function assignEnvs(): void
    {
        $this->smtpAccount = getenv('EMAIL_NOTIFIER_SMTP_ACCOUNT');
        $this->appKey = getenv('EMAIL_NOTIFIER_APP_KEY');
        $this->secretKey = getenv('EMAIL_NOTIFIER_SECRET_KEY');
        $this->projectName = getenv('SMS_NOTIFIER_PROJECT_NAME');

        $receivers = getenv('EMAIL_NOTIFIER_RECEIVERS');
        foreach (json_decode($receivers) as $receiver) {
            $this->receivers[$receiver] = '';
        }
    }
}
