<?php

namespace Cora;

use Cora\Auth\TokenProviderInterface;
use Cora\Http\HttpClient;
use Cora\Util\Idempotency;

final class CoraClient
{
    /** @var Config */
    private $config;
    /** @var TokenProviderInterface */
    private $tokenProvider;

    public function __construct(Config $config, TokenProviderInterface $tokenProvider)
    {
        $this->config        = $config;
        $this->tokenProvider = $tokenProvider;
    }

    private function http(): HttpClient
    {
        $token = $this->tokenProvider->getToken();
        $client = new HttpClient($this->config->getBaseUrl(), [
            'Content-Type' => 'application/json',
        ]);
        return $client->withBearer($token['access_token']);
    }

    /**
     * Emissão de boleto registrado v2
     * POST /v2/invoices
     */
    public function issueRegisteredBoletoV2(array $payload, ?string $idempotencyKey = null): array
    {
        $headers = [];
        if ($idempotencyKey === null) {
            $idempotencyKey = Idempotency::uuidV4();
        }
        $headers['Idempotency-Key'] = $idempotencyKey;

        return $this->http()->request('POST', '/v2/invoices', [
            'headers' => $headers,
            'json'    => $payload,
        ]);
    }

    /**
     * Consultar boletos v2 (listagem)
     * GET /v2/invoices
     * Filtros: start, end, state, search, page, perPage
     */
    public function listBoletosV2(array $filters = []): array
    {
        return $this->http()->request('GET', '/v2/invoices', [
            'query' => $filters
        ]);
    }

    /**
     * Consultar detalhes de um boleto v2
     * GET /v2/invoices/{id}
     */
    public function getBoletoDetailsV2(string $invoiceId): array
    {
        return $this->http()->request('GET', '/v2/invoices/' . urlencode($invoiceId));
    }

    /**
     * Pagar boleto em STAGE
     * POST /v2/invoices/pay (apenas stage)
     */
    public function payBankslipStage(string $invoiceId, ?string $idempotencyKey = null): array
    {
        if ($this->config->getEnvironment() !== Config::ENV_STAGE) {
            throw new \InvalidArgumentException('Pagamento de boleto por API disponível apenas no ambiente STAGE.');
        }

        $headers = [];
        if ($idempotencyKey === null) {
            $idempotencyKey = Idempotency::uuidV4();
        }
        $headers['Idempotency-Key'] = $idempotencyKey;

        return $this->http()->request('POST', '/v2/invoices/pay', [
            'headers' => $headers,
            'json'    => ['id' => $invoiceId],
        ]);
    }
}
