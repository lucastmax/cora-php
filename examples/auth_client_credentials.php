
<?php
require __DIR__ . '/../vendor/autoload.php';

use Cora\Config;
use Cora\Auth\ClientCredentialsProvider;

$config = new Config(
    getenv('CORA_ENV') ?: Config::ENV_STAGE,
    getenv('CORA_CLIENT_ID') ?: 'CLIENT_ID',
    getenv('CORA_CLIENT_SECRET') ?: 'CLIENT_SECRET',
    ['offline_access','invoice']
);

$provider = new ClientCredentialsProvider($config);
$token = $provider->getToken();

print_r($token);
