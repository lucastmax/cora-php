
<?php
namespace Cora\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Cora\Exceptions\HttpException;

final class HttpClient
{
    /** @var Client */
    private $client;

    /** @var array */
    private $defaultHeaders;

    public function __construct(string $baseUri, array $defaultHeaders = [], array $guzzleOptions = [])
    {
        $this->defaultHeaders = $defaultHeaders;
        $this->client = new Client(array_merge([
            'base_uri' => rtrim($baseUri, '/').'/',
            'timeout'  => 30,
        ], $guzzleOptions));
    }

    public function withBearer(string $token): self
    {
        $clone = clone $this;
        $clone->defaultHeaders['Authorization'] = 'Bearer '.$token;
        return $clone;
    }

    /**
     * @return mixed array|scalar|string
     * @throws HttpException
     */
    public function request(string $method, string $uri, array $options = [])
    {
        $options['headers'] = array_merge($this->defaultHeaders, $options['headers'] ?? []);
        try {
            $res = $this->client->request($method, ltrim($uri, '/'), $options);
            $body = (string) $res->getBody();
            $json = json_decode($body, true);
            return $json === null && json_last_error() !== JSON_ERROR_NONE ? $body : $json;
        } catch (RequestException $e) {
            $status = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
            $body   = $e->getResponse() ? (string) $e->getResponse()->getBody() : null;
            throw new HttpException('HTTP error', $status, $body, $e);
        }
    }
}
