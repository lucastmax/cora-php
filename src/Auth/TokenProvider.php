<?php

namespace Cora\Auth;

use Cora\Config;
use Cora\Http\HttpClient;
use Cora\Exceptions\AuthException;

final class TokenProvider implements TokenProviderInterface
{
    /** @var array|null */
    private $cachedToken;

    public function __construct(Config $config, $token)
    {
        $this->config = $config;
        $this->http   = new HttpClient($config->getBaseUrl(), [
            'Content-Type' => 'application/json',
        ]);

        $this->cachedToken = $token;
    }

    public function getToken(): array
    {
        return ["access_token" => $this->cachedToken];
    }
}
