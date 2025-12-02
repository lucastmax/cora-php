
<?php
require __DIR__ . '/../vendor/autoload.php';

use Cora\Config;
use Cora\CoraClient;
use Cora\Auth\ClientCredentialsProvider;

$config   = new Config('stage', 'CLIENT_ID', 'CLIENT_SECRET', ['offline_access','invoice']);
$provider = new ClientCredentialsProvider($config);
$client   = new CoraClient($config, $provider);

$invoiceId = 'ID_DO_BOLETO'; // substitua
print_r($client->getBoletoDetailsV2($invoiceId));
