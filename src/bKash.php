<?php

namespace RT\bKash;

class bKash
{
    private $credential;
    private $baseURL;

    public function __construct(array $credential)
    {
        $this->validateCredential($credential);
        $this->credential = $credential;
        $this->baseURL = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/';
    }

    public function createPayment(array $requestData)
    {
        $this->validateRequestData($requestData);

        $fields = [
            'mode'                      => '0011',
            'payerReference'            => !empty($requestData['payer_reference']) ? $requestData['payer_reference'] : $this->generatePayerReference(),
            'callbackURL'               => $requestData['success_url'],
            'amount'                    => $requestData['amount'],
            'currency'                  => 'BDT',
            'intent'                    => 'sale',
            'merchantInvoiceNumber'     => !empty($requestData['invoice_number']) ? $requestData['invoice_number'] : $this->generateMerchantInvoiceNumber($requestData['brand_name'])
        ];

        $result = $this->sendRequest('POST', 'create', $fields, 'payment');
        if ($result['statusCode'] === '0000') {
            return $result['bkashURL'];
        }
        throw new \Exception($result['statusMessage']);
    }

    public function verifyPayment($paymentID)
    {
        $fields = [
            'paymentID' => $paymentID
        ];
        $result = $this->sendRequest('POST', 'execute', $fields, 'payment');
        if (!isset($result['transactionStatus']) && !isset($result['errorCode'])) {
            $result = $this->queryPayment($paymentID);
        }

        return $result;
    }

    private function queryPayment($paymentID)
    {
        $fields = [
            'paymentID' => $paymentID
        ];
        return $this->sendRequest('POST', 'payment/status', $fields, 'payment');
    }

    private function sendRequest($method, $endPoint, array $data, $requestType)
    {
        $headers = ($requestType === 'payment') ? $this->getPaymentHeaders() : $this->getAuthTokenHeaders();

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseURL . $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new \Exception("cURL Error: " . curl_error($curl));
        }
        curl_close($curl);

        return json_decode($response, true);
    }

    private function getAuthTokenHeaders()
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'username: ' . $this->credential['username'],
            'password: ' . $this->credential['password'],
        ];

        return $headers;
    }

    private function getPaymentHeaders()
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $this->getAuthToken(),
            'X-App-Key: ' . $this->credential['app_key'],
        ];

        return $headers;
    }

    private function getAuthToken()
    {
        $fields = [
            'app_key' => $this->credential['app_key'],
            'app_secret' => $this->credential['app_secret'],
        ];

        $result = $this->sendRequest('POST', 'token/grant', $fields, 'token');
        if ($result['statusCode'] === '0000') {
            return $result['id_token'];
        }
        throw new \Exception($result['statusMessage']);
    }

    private function validateCredential(array $credential)
    {
        $requiredKeys = ['app_key', 'app_secret', 'username', 'password'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $credential)) {
                throw new \InvalidArgumentException("Missing required credential: $key");
            }
        }
    }

    private function validateRequestData(array $requestData)
    {
        $requiredKeys = ['amount', 'success_url', 'brand_name'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $requestData)) {
                throw new \InvalidArgumentException("Missing required field: $key");
            }
        }
    }

    private function generatePayerReference()
    {
        return date('Ymdhis');
    }

    private function generateMerchantInvoiceNumber($brandName)
    {
        return uniqid($brandName);
    }
}
