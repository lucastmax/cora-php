<?php

namespace Cora\Auth;

use Cora\Config;
use Cora\Http\HttpClient;
use Cora\Exceptions\AuthException;

final class TokenProvider implements TokenProviderInterface
{
    /** @var array|null */
    private $cachedToken;

    public function __construct(Config $config, $refreshToken)
    {
        $this->config = $config;
        $this->http   = new HttpClient($config->getBaseUrl(), [
            'Content-Type' => 'application/json',
        ]);

        $authHeader = 'Basic ' . base64_encode($this->config->getClientId() . ':' . $this->config->getClientSecret());
        $token = $this->http->request('POST', $this->config->getOauthTokenUrl(), [
            'headers' => [
                'Authorization' => $authHeader,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ],
        ]);

        if (!is_array($token) || empty($token['access_token'])) {
            throw new AuthException('Token inválido em client_credentials');
        }
        $token['_obtained_at'] = time();
        $this->cachedToken = $token;
    }

    public function getToken() :  array
    {
        return $this->cachedToken;
    }
}
