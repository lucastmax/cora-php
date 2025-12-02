
# tmax/cora-php

Cliente PHP 7 para as APIs da **Cora**, cobrindo:
- Emissão de boleto registrado v2
- Consultar boletos v2
- Consultar detalhes de um boleto v2
- Pagar boleto em **Stage**
- OAuth2 **Client Credentials**

## Requisitos
- PHP >= 7.2.5
- ext-json
- Composer

## Instalação (via Packagist)
```bash
composer require tmax/cora-php
```

## Uso rápido (Client Credentials)
```php
use Cora\Config;
use Cora\Auth\ClientCredentialsProvider;
use Cora\CoraClient;

$config = new Config(
    Config::ENV_STAGE,               // ou Config::ENV_PROD
    'CLIENT_ID',
    'CLIENT_SECRET',
    ['offline_access','invoice']     // ajuste escopos
);

$provider = new ClientCredentialsProvider($config);
$client   = new CoraClient($config, $provider);

// 1) Emitir boleto
$payload = [
  "customer" => [
    "name" => "Cliente Exemplo",
    "document" => "12345678909",
    "email" => "cliente@exemplo.com"
  ],
  "services" => [
    ["name" => "Mensalidade", "description" => "Ref. Outubro", "amount" => 10000]
  ],
  "payment_terms" => [
    "due_date" => date('Y-m-d', strtotime('+5 days')),
    "fine"     => ["amount" => 200],
    "interest" => ["amount" => 100]
  ]
];

$res = $client->issueRegisteredBoletoV2($payload);

// 2) Listar boletos
$list = $client->listBoletosV2([
  'start' => date('Y-m-01'),
  'end'   => date('Y-m-d'),
  'state' => 'OPEN',
]);

// 3) Detalhes de um boleto
$details = $client->getBoletoDetailsV2($res['id']);

// 4) Pagar boleto (apenas stage)
$paid = $client->payBankslipStage($res['id']);
```

## Ambientes
- **Stage**: https://api.stage.cora.com.br
- **Prod**:  https://api.cora.com.br

## Boas práticas
- Use **Idempotency-Key** em POSTs.
- Valores monetários sempre em **centavos**.
- Trate expiração do token (renove próximo de expirar).
- Valide que o escopo correto está habilitado (p.ex. `invoice`).

## Licença
MIT
