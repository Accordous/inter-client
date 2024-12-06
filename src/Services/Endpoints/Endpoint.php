<?php

namespace Accordous\InterClient\Services\Endpoints;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

abstract class Endpoint
{
    protected $http;

    public function __construct(PendingRequest $http)
    {
        $this->http = $http;
    }

    protected function client(): PendingRequest
    {
        return $this->http;
    }

    protected function getApiVersion()
    {
        return Config::get('inter.api');
    }

    protected function getApiCobrancaVersion()
    {
        return Config::get('inter.api_cobranca_version');
    }

    protected function validate(array $attributes, array $rules, array $messages): array
    {
        return Validator::validate($attributes, $rules, $messages);
    }
}
