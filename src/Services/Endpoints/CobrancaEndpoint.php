<?php

namespace Accordous\InterClient\Services\Endpoints;

class CobrancaEndpoint extends Endpoint
{
    private const BASE_URI = '/cobranca';

    /**
     * @param array $attributes
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function emitirBoleto(array $attributes)
    {
        return $this->client()->post(self::BASE_URI . parent::getApiVersion() . '/boletos', $this->validate($attributes, $this->emitirBoletoRules(), $this->emitirBoletoMessages()));
    }

    /**
     * @param string $nossoNumero
     * @param string $motivo
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function cancelarBoleto(string $nossoNumero, $motivo)
    {
        $attributes = [
            'motivoCancelamento' => $motivo,
        ];

        return $this->client()->post(self::BASE_URI . parent::getApiVersion() . "/boletos/$nossoNumero/cancelar", $this->validate($attributes, $this->cancelarBoletoRules(), $this->cancelarBoletoMessages()));
    }

    /**
     * @param string $nossoNumero
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function detalharBoleto(string $nossoNumero)
    {
        return $this->client()->get(self::BASE_URI . parent::getApiVersion() . "/boletos/$nossoNumero");
    }

    /**
     * @param string $nossoNumero
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function pdfBoleto(string $nossoNumero)
    {
        return $this->client()->get(self::BASE_URI . parent::getApiVersion() . "/boletos/$nossoNumero/pdf");
    }

    /**
     * @param string $url
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function criarWebhook(string $url)
    {
        return $this->client()->put(self::BASE_URI . parent::getApiVersion() . "/boletos/webhook", $this->validate(['webhookUrl' => $url], $this->criarWebhookRules(), $this->criarWebhookMessages()));
    }

    /**
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function deletarWebhook()
    {
        return $this->client()->delete(self::BASE_URI . parent::getApiVersion() . "/boletos/webhook");
    }

    private function emitirBoletoRules(): array
    {
        return [
            'seuNumero' => 'required|string',
            'valorNominal' => 'required|numeric',
            'dataVencimento' => 'required|date_format:Y-m-d',
            'numDiasAgenda' => 'required|integer|min:0|max:60',

            'pagador' => 'required',
            'pagador.cpfCnpj' => 'required|string',
            'pagador.tipoPessoa' => 'required|in:FISICA,JURIDICA',
            'pagador.nome' => 'required|string',
            'pagador.endereco' => 'required|string',
            'pagador.numero' => 'nullable|string',
            'pagador.complemento' => 'nullable|string',
            'pagador.bairro' => 'nullable|string',
            'pagador.cidade' => 'required|string',
            'pagador.uf' => 'required|string',
            'pagador.cep' => 'required|string',
            'pagador.email' => 'nullable|string|email',
            'pagador.ddd' => 'nullable|string|size:2',
            'pagador.telefone' => 'nullable|string|max:9',

            'mensagem' => 'nullable',
            'mensagem.linha1' => 'nullable|string',
            'mensagem.linha2' => 'nullable|string',
            'mensagem.linha3' => 'nullable|string',
            'mensagem.linha4' => 'nullable|string',
            'mensagem.linha5' => 'nullable|string',

            'desconto1' => 'nullable',
            'desconto1.codigoDesconto' => 'required_if:desconto1,!=,null|string|in:NAOTEMDESCONTO,VALORFIXODATAINFORMADA,PERCENTUALDATAINFORMADA',
            'desconto1.data' => 'nullable|date_format:Y-m-d',
            'desconto1.taxa' => 'required_if:desconto1,!=,null|numeric',
            'desconto1.valor' => 'required_if:desconto1,!=,null|numeric',

            'desconto2' => 'nullable',
            'desconto2.codigoDesconto' => 'required_if:desconto2,!=,null|string|in:NAOTEMDESCONTO,VALORFIXODATAINFORMADA,PERCENTUALDATAINFORMADA',
            'desconto2.data' => 'nullable|date_format:Y-m-d',
            'desconto2.taxa' => 'required_if:desconto2,!=,null|numeric',
            'desconto2.valor' => 'required_if:desconto2,!=,null|numeric',

            'desconto3' => 'nullable',
            'desconto3.codigoDesconto' => 'required_if:desconto3,!=,null|string|in:NAOTEMDESCONTO,VALORFIXODATAINFORMADA,PERCENTUALDATAINFORMADA',
            'desconto3.data' => 'nullable|date_format:Y-m-d',
            'desconto3.taxa' => 'required_if:desconto3,!=,null|numeric',
            'desconto3.valor' => 'required_if:desconto3,!=,null|numeric',

            'multa' => 'nullable',
            'multa.codigoMulta' => 'required_if:multa,!=,null|string|in:NAOTEMMULTA,VALORFIXO,PERCENTUAL',
            'multa.data' => 'nullable|date_format:Y-m-d',
            'multa.taxa' => 'required_if:multa,!=,null|numeric',
            'multa.valor' => 'required_if:multa,!=,null|numeric',

            'mora' => 'nullable',
            'mora.codigoMora' => 'required_if:mora,!=,null|string|in:VALORDIA,TAXAMENSAL,ISENTO',
            'mora.data' => 'required_if:mora.codigoMora,VALORDIA,TAXAMENSAL|date_format:Y-m-d',
            'mora.taxa' => 'required_if:mora.codigoMora,TAXAMENSAL|numeric',
            'mora.valor' => 'required_if:mora,!=,null|numeric',

            'beneficiarioFinal' => 'nullable',
            'beneficiarioFinal.nome' => 'required_if:beneficiarioFinal,!=,null|string',
            'beneficiarioFinal.cpfCnpj' => 'required_if:beneficiarioFinal,!=,null|string',
            'beneficiarioFinal.tipoPessoa' => 'required_if:beneficiarioFinal,!=,null|string|in:FISICA,JURIDICA',
            'beneficiarioFinal.cep' => 'required_if:beneficiarioFinal,!=,null|string',
            'beneficiarioFinal.endereco' => 'required_if:beneficiarioFinal,!=,null|string',
            'beneficiarioFinal.bairro' => 'nullable|string',
            'beneficiarioFinal.cidade' => 'required_if:beneficiarioFinal,!=,null|string',
            'beneficiarioFinal.uf' => 'required_if:beneficiarioFinal,!=,null|string',
        ];
    }

    private function emitirBoletoMessages(): array
    {
        return [
            'seuNumero.required' => 'Seu número é obrigatório.',
            'valorNominal.required' => 'Valor nominal é obrigatório.',
            'dataVencimento.required' => 'Data vencimento é obrigatório.',
            'numDiasAgenda.required' => 'Dias para cancelamento é obrigatório.',
        ];
    }

    private function cancelarBoletoRules(): array
    {
        return [
            'motivoCancelamento' => 'required|in:ACERTOS,APEDIDODOCLIENTE,PAGODIRETOAOCLIENTE,SUBSTITUICAO',
        ];
    }

    private function cancelarBoletoMessages(): array
    {
        return [
            'motivoCancelamento' => 'Motivo do cancelamento é obrigatório.',
        ];
    }

    private function criarWebhookRules(): array
    {
        return [
            'webhookUrl' => 'required|string|url',
        ];
    }

    private function criarWebhookMessages(): array
    {
        return [
            'webhookUrl' => 'URL é obrigatório.',
        ];
    }
}
