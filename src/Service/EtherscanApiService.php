<?php

namespace App\Service;

use Exception;

class EtherscanApiService
{
    /** @var string */
    private $apiUrl;
    /** @var string */
    private $apiKey;

    /**
     * EtherscanApiService constructor.
     * @param string $apiDomain
     * @param string $apiKey
     */
    public function __construct(string $apiDomain, string $apiKey)
    {
        $this->apiUrl = 'https://' . $apiDomain . '/api';
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $address
     * @param int $fromBlock
     * @return array
     * @throws Exception
     */
    public function getLogs(string $address, int $fromBlock = 0): array
    {
        return $this->sendRequest([
            'module' => 'logs',
            'action' => 'getLogs',
            'address' => $address,
            'fromBlock' => $fromBlock
        ]);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    private function sendRequest(array $data)
    {
        $data['apikey'] = $this->apiKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Error while requesting Etherscan API');
        }
        curl_close($ch);
        $result = json_decode($json, true);
        if (empty($result) || !is_array($result) || !empty($result['error']) || !isset($result['result'])) {
            throw new Exception('Invalid response from Etherscan API: ' . $json);
        }
        usleep(200000);
        return $result['result'];
    }
}