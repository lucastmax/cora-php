
<?php
namespace Cora\Auth;

use Cora\Config;
use Cora\Http\HttpClient;
use Cora\Exceptions\AuthException;

final class ClientCredentialsProvider implements TokenProviderInterface
{
    /** @var Config */
    private $config;

    /** @var HttpClient */
    private $http;

    /** @var array|null */
    private $cachedToken;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->http   = new HttpClient($config->getBaseUrl(), [
            'Content-Type' => 'application/json',
        ]);
    }

    public function getToken(): array
    {
        if ($this->cachedToken && $this->isValid($this->cachedToken)) {
            return $this->cachedToken;
        }

        $authHeader = 'Basic ' . base64_encode($this->config->getClientId() . ':' . $this->config->getClientSecret());
        $token = $this->http->request('POST', $this->config->getOauthTokenUrl(), [
            'headers' => [
                'Authorization' => $authHeader,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
                'scope'      => implode(' ', $this->config->getScopes()),
            ],
        ]);

        if (!is_array($token) || empty($token['access_token'])) {
            throw new AuthException('Token inválido em client_credentials');
        }
        $token['_obtained_at'] = time();
        $this->cachedToken = $token;
        return $token;
    }

    private function isValid(array $token): bool
    {
        if (!isset($token['_obtained_at'], $token['expires_in'])) return false;
        // Renova 2 minutos antes de expirar
        return (time() - $token['_obtained_at']) < ($token['expires_in'] - 120);
    }
}
