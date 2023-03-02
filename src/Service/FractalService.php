<?php

namespace App\Service;

use App\Exception\FractalException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FractalService
{
    /** @var string */
    private $appId;
    /** @var string */
    private $appSecret;
    /** @var string */
    private $frontendDomain;
    /** @var string */
    private $authDomain;
    /** @var string */
    private $resourceDomain;
    /** @var string */
    private $redirectUri;

    /**
     * FractalService constructor.
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $fractalConfig = $parameterBag->get('fractal');
        $this->appId = $fractalConfig['appToken'];
        $this->appSecret = $fractalConfig['appSecret'];
        $this->frontendDomain = $fractalConfig['frontendDomain'];
        $this->authDomain = $fractalConfig['authDomain'];
        $this->resourceDomain = $fractalConfig['resourceDomain'];
        $this->redirectUri = $parameterBag->get('apiDomain') . '/fractal-login';
    }

    /**
     * @param string $state
     * @return string
     */
    public function getAuthLink(string $state): string
    {
        $data = [
            'client_id'     => $this->appId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'scope'         => '',//ToDo
            'state'         => $state,
        ];
        return 'https://' . $this->frontendDomain . '/authorize?' . http_build_query($data);
    }

    /**
     * @param string $code
     * @return array
     * @throws FractalException
     */
    public function getAccessToken(string $code): array
    {
        return $this->makePostRequest($this->authDomain, '/oauth/token', [
            'client_id'     => $this->appId,
            'client_secret' => $this->appSecret,
            'code'          => $code,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->redirectUri,
        ]);
    }

    /**
     * @param string $accessToken
     * @return array
     * @throws FractalException
     */
    public function getUserInfo(string $accessToken): array
    {
        return $this->makeGetRequest($this->resourceDomain, '/users/me', [], [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
        ]);
    }

    /**
     * @param string $domain
     * @param string $path
     * @param bool $post
     * @param array $postData
     * @param array $headers
     * @return array
     * @throws FractalException
     */
    private function makeApiRequest(string $domain, string $path, bool $post, array $postData, array $headers): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://' . $domain . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            if (!empty($postData)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new FractalException();
        }
        curl_close($ch);
        $result = json_decode($result);
        return $result;
    }

    /**
     * @param string $domain
     * @param string $path
     * @param array $data
     * @param array $headers
     * @return array
     * @throws FractalException
     */
    private function makeGetRequest(string $domain, string $path, array $data = [], array $headers = []): array
    {
        if ($data) {
            $path .= '?' . http_build_query($data);
        }
        return $this->makeApiRequest($domain, $path, false, [], $headers);
    }

    /**
     * @param string $domain
     * @param string $path
     * @param array $data
     * @param array $headers
     * @return array
     * @throws FractalException
     */
    private function makePostRequest(string $domain, string $path, array $data = [], array $headers = []): array
    {
        return $this->makeApiRequest($domain, $path, true, $data, $headers);
    }
}