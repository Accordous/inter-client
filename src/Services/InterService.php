<?php

namespace Accordous\InterClient\Services;

use Accordous\InterClient\Services\Endpoints\CobrancaEndpoint;
use Accordous\InterClient\Services\Endpoints\OAuthEndpoint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class InterService
{
    private const TOKEN_TIME = 3600;

    private const RETRY_TIMES = 5;
    private const RETRY_SLEEP = 10;

    /**
     * @var \Illuminate\Http\Client\PendingRequest
     */
    private $http;

    /**
     * @var OAuthEndpoint
     */
    private $oAuth;

    /**
     * @var CobrancaEndpoint
     */
    private $cobranca;

    /**
     * InterService constructor.
     */
    public function __construct($clientId, $clientSecret, $options, $token = null)
    {
        $httpOAuth = Http::baseUrl(Config::get('inter.host'))
            ->withHeaders([
                'Cache-Control' => 'no-cache',
            ])
            ->withOptions($options)
            ->retry(self::RETRY_TIMES, self::RETRY_SLEEP)
            ->asForm();

        $this->http = Http::baseUrl(Config::get('inter.host'))
            ->withHeaders([
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
            ])
            ->retry(self::RETRY_TIMES, self::RETRY_SLEEP)
            ->withOptions($options);

        $this->oAuth = new OAuthEndpoint($httpOAuth);
        $this->cobranca = new CobrancaEndpoint($this->http);

        if ($token === null) {
            $tokenKey = 'token_' . $clientId;

            $token = cache()->remember($tokenKey, self::TOKEN_TIME, fn () => $this->getToken($clientId, $clientSecret));
        }

        $this->http->withToken($token);
    }

    /**
     * @return OAuthEndpoint
     */
    public function oAuth(): OAuthEndpoint
    {
        return $this->oAuth;
    }

    /**
     * @return CobrancaEndpoint
     */
    public function cobranca(): CobrancaEndpoint
    {
        return $this->cobranca;
    }

    public function getToken($clientId, $clientSecret)
    {
        return $this->oAuth->token([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ])->json('access_token');
    }
}
