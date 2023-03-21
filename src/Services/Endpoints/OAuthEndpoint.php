<?php

namespace Accordous\InterClient\Services\Endpoints;

class OAuthEndpoint extends Endpoint
{
    private const BASE_URI = '/oauth';

    /**
     * @param array $attributes
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function token(array $attributes)
    {
        $attributes['grant_type'] = 'client_credentials';
        $attributes['scope'] = 'boleto-cobranca.read boleto-cobranca.write';
        //pagamento-boleto.write pagamento-boleto.read pagamento-darf.write cob.write cob.read pix.write pix.read webhook.read webhook.write payloadlocation.write payloadlocation.read

        return $this->client()->post(self::BASE_URI . parent::getApiVersion() . '/token', $this->validate($attributes, $this->rules(), $this->messages()));
    }

    private function rules(): array
    {
        return [
            'client_id' => 'required',
            'client_secret' => 'required',
            'grant_type' => 'required',
            'scope' => 'required',
        ];
    }

    private function messages(): array
    {
        return [
            'client_id' => 'Id do cliente é obrigatório.',
            'client_secret' => 'Segredo do cliente é obrigatório.',
        ];
    }
}
