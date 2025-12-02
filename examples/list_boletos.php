
<?php
require __DIR__ . '/../vendor/autoload.php';

use Cora\Config;
use Cora\CoraClient;
use Cora\Auth\ClientCredentialsProvider;

$config   = new Config('stage', 'CLIENT_ID', 'CLIENT_SECRET', ['offline_access','invoice']);
$provider = new ClientCredentialsProvider($config);
$client   = new CoraClient($config, $provider);

$filters = [
  'start'   => date('Y-m-01'),
  'end'     => date('Y-m-d'),
  'state'   => 'OPEN',
  'page'    => 1,
  'perPage' => 50
];

print_r($client->listBoletosV2($filters));
