<?php

namespace App\Service;

use Exception;

class GethApiService
{
    /** @var string */
    private $apiAddress;

    /**
     * GethApiService constructor.
     * @param string $apiAddress
     */
    public function __construct(string $apiAddress)
    {
        $this->apiAddress = $apiAddress;
    }

    /**
     * @param string $message
     * @param string $account
     * @param string $password
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function sign(string $message, string $account, string $password, int $id = 1): string
    {
        return $this->sendRequest([
            'method' => 'personal_sign',
            'params' => [$message, $account, $password],
            'id' => $id
        ]);
    }

    /**
     * @param string $message
     * @param string $signature
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function recoverSignerAddress(string $message, string $signature, int $id = 1): string
    {
        return $this->sendRequest([
            'method' => 'personal_ecRecover',
            'params' => [$message, $signature],
            'id' => $id
        ]);
    }

    /**
     * @param string $password
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function createNewAccount(string $password, int $id = 1): string
    {
        return $this->sendRequest([
            'method' => 'personal_newAccount',
            'params' => [$password],
            'id' => $id
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function getCurrentBlockNumber(int $id = 1): string
    {
        return $this->sendRequest([
            'method' => 'eth_blockNumber',
            'params' => [],
            'id' => $id
        ]);
    }

    /**
     * @param string $contractAddress
     * @param string $fromBlock
     * @param string $toBlock
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getLogs(string $contractAddress, string $fromBlock, string $toBlock, int $id = 1): array
    {
        return $this->sendRequest([
            'method' => 'eth_getLogs',
            'params' => [[
                'address' => $contractAddress,
                'fromBlock' => $fromBlock,
                'toBlock' => $toBlock
            ]],
            'id' => $id
        ]);
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function listAccounts(int $id = 1): array
    {
        return $this->sendRequest([
            'method' => 'personal_listAccounts',
            'id' => $id
        ]);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    private function sendRequest(array $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiAddress);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Error while requesting Geth RPC');
        }
        curl_close($ch);
        $result = json_decode($json, true);
        if (empty($result) || !is_array($result) || !isset($result['result'])) {
            throw new Exception('Invalid response from Geth RPC: ' . $json);
        }
        return $result['result'];
    }
}