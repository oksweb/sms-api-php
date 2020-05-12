<?php

namespace OKSWeb\SMS;

/**
 * Class Gateway.
 */
class Gateway
{
    private $apiKey;
    private $apiURL            = 'https://sms-{{server-id}}.oksweb.com/api/v{{api-version}}/sms';
    private $serverId          = 1;
    private $apiVersion        = 1;
    private $maxNetworkRetries = 0;
    private $networkTimeout    = 20;
    private $connectTimeout    = 20;

    const VERSION = '0.0.1';

    private $lastError;

    /**
     * API constructor.
     *
     * @param string $apiKey
     * @param int    $serverId
     */
    public function __construct($apiKey, $serverId = 1)
    {
        $this->apiKey   = $apiKey;
        $this->serverId = $serverId;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     *
     * @return Gateway
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiURL()
    {
        $serverId = \str_pad($this->serverId, 2, '0', STR_PAD_LEFT);
        $apiURL   = \str_replace('{{server-id}}', $serverId, $this->apiURL);

        return \str_replace('{{api-version}}', $this->apiVersion, $apiURL);
    }

    /**
     * @return int
     */
    public function getServerId()
    {
        return $this->serverId;
    }

    /**
     * @param int $serverId
     *
     * @return Gateway
     */
    public function setServerId($serverId)
    {
        $this->serverId = (int) $serverId;

        return $this;
    }

    /**
     * @return int
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param int $apiVersion
     *
     * @return Gateway
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = (int) $apiVersion;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxNetworkRetries()
    {
        return $this->maxNetworkRetries;
    }

    /**
     * @param int $maxNetworkRetries
     *
     * @return Gateway
     */
    public function setMaxNetworkRetries($maxNetworkRetries)
    {
        $this->maxNetworkRetries = (int) $maxNetworkRetries;

        return $this;
    }

    /**
     * @return int
     */
    public function getNetworkTimeout()
    {
        return $this->networkTimeout;
    }

    /**
     * @param int $networkTimeout
     *
     * @return Gateway
     */
    public function setNetworkTimeout($networkTimeout)
    {
        $this->networkTimeout = (int) $networkTimeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * @param int $connectTimeout
     *
     * @return Gateway
     */
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = (int) $connectTimeout;

        return $this;
    }

    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * @param null $lastError
     *
     * @return Gateway
     */
    public function setLastError($lastError)
    {
        $this->lastError = $lastError;

        return $this;
    }

    /**
     * @param string $to
     * @param string $message
     * @param int    $senderId
     *
     * @return string
     */
    public function sendQuickSMS($to, $message, $senderId = null)
    {
        $sms = new SMS();

        $sms
            ->setReceiver($to)
            ->setMessage($message)
            ->setSenderId($senderId)
        ;

        return $this->sendSMS($sms);
    }

    /**
     * @param SMS $sms
     *
     * @return string the API key used for requests
     */
    public function sendSMS($sms)
    {
        if (empty($sms->getMessage())) {
            $this->lastError = 'SMS_EMPTY_BODY';

            return false;
        }

        if (empty($sms->getReceiver())) {
            $this->lastError = 'SMS_EMPTY_RECEIVER';

            return false;
        }

        if (!\is_int($sms->getSenderId())) {
            $this->lastError = 'SMS_SENDER_ID_MUST_BE_INTEGER';

            return false;
        }

        $body = [
            'action'    => 'send-sms',
            'to'        => $sms->getReceiver(),
            'sender_id' => $sms->getSenderId(),
            'sms'       => $sms->getMessage(),
            'unicode'   => $sms->isUnicode() ? 1 : 0,
        ];

        return $this->makeRequest($body);
    }

    /**
     * @return string the API key used for requests
     */
    public function checkBalance()
    {
        $body = [
            'action' => 'check-balance',
        ];

        return $this->makeRequest($body);
    }

    private function makeRequest($body)
    {
        $preparedBody = $this->makeBody($body);

        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_URL, $this->getApiURL());
        \curl_setopt($ch, CURLOPT_POST, 1);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $preparedBody);
        \curl_setopt($ch, CURLOPT_TIMEOUT, $this->getNetworkTimeout());
        \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->getConnectTimeout());
        $response = \curl_exec($ch);
        \curl_close($ch);

        return \json_decode($response, true);
    }

    private function makeBody($body)
    {
        $body['api_key'] = $this->getApiKey();

        $preparedBody = '';

        foreach ($body as $key => $value) {
            $preparedBody .= $key . '=' . \urlencode($value) . '&';
        }

        return \rtrim($preparedBody, '&');
    }
}
