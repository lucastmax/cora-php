
<?php
require __DIR__ . '/../vendor/autoload.php';

use Cora\Config;
use Cora\CoraClient;
use Cora\Auth\ClientCredentialsProvider;

$config   = new Config('stage', 'CLIENT_ID', 'CLIENT_SECRET', ['offline_access','invoice']);
$provider = new ClientCredentialsProvider($config);
$client   = new CoraClient($config, $provider);

$payload = [
  "customer" => [
    "name" => "Cliente Exemplo",
    "document" => "12345678909",
    "email" => "cliente@exemplo.com"
  ],
  "services" => [
    [
      "name" => "Mensalidade",
      "description" => "Ref. Outubro",
      "amount" => 10000
    ]
  ],
  "payment_terms" => [
    "due_date" => date('Y-m-d', strtotime('+5 days')),
    "fine"     => ["amount" => 200],
    "interest" => ["amount" => 100]
  ]
];

try {
  $res = $client->issueRegisteredBoletoV2($payload);
  print_r($res);
} catch (\Throwable $e) {
  echo $e->getMessage();
}
