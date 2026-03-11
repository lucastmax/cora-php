<?php

namespace Cora;

final class Config
{
    const ENV_STAGE = 'stage';
    const ENV_PROD  = 'prod';

    /** @var string stage|prod */
    private $environment;

    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var string[] */
    private $scopes;

    /** @var string */
    private $baseUrl;

    /** @var string */
    private $oauthTokenUrl;

    private $code;

    private $redirectUrl;

    public function __construct(
        string $environment,
        string $clientId,
        string $clientSecret,
        array $scopes = ['offline_access']
    ) {
        $this->environment   = $environment;
        $this->clientId      = $clientId;
        $this->clientSecret  = $clientSecret;
        $this->scopes        = $scopes;

        if ($environment === self::ENV_STAGE) {
            $this->baseUrl       = 'https://api.stage.cora.com.br';
            $this->oauthTokenUrl = 'https://api.stage.cora.com.br/oauth/token';
        } else {
            $this->baseUrl       = 'https://api.cora.com.br';
            $this->oauthTokenUrl = 'https://api.cora.com.br/oauth/token';
        }
    }

    public function getEnvironment(): string { return $this->environment; }
    public function getClientId(): string { return $this->clientId; }
    public function getClientSecret(): string { return $this->clientSecret; }
    public function getScopes(): array { return $this->scopes; }
    public function getBaseUrl(): string { return $this->baseUrl; }
    public function getOauthTokenUrl(): string { return $this->oauthTokenUrl; }
    public function getCode() : string {return $this->code;}
    public function getRedirectUrl() : string {return $this->redirectUrl;}

    public function setCode($code){ $this->code = $code; }
    public function setRedirectUrl($url) { $this->redirectUrl = $url;}
    

}
