
<?php
namespace Cora\Auth;

interface TokenProviderInterface
{
    /** Returns ['access_token' => '...', 'expires_in' => 86400, ...] */
    public function getToken(): array;
}
