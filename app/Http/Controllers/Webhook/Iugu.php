<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Iugu\CredencialIugu;
use Illuminate\Http\Request;


class Iugu extends Controller
{

    public function faturaStatus(Request $request)
    {
       
        $event = $request->input('event');
        $data = $request->input('data');

        // lógica para outros eventos, se necessário
        if ($event === 'invoice.status_changed') {
            $this->alteraStatusFatura($data,$event);
        }
    }

    public function alteraStatusFatura($data,$event)
    {
        // Lógica para lidar com o status da fatura
        
        $credencial_iugu = CredencialIugu::where('id_cliente_iugu', $data['account_id'])->first();

        if ($credencial_iugu) {
            $fatura_iugu = $credencial_iugu->faturaIugu;
            //  Log::info('Resposta da API Iugu:', ['response' => $fatura_iugu]);
            if ($fatura_iugu) {
                $update =  [
                    'status_fatura' => $data['status'],
                ];
                if ($data['payment_method']) {
                    $update['tipo_pagamento'] = $data['payment_method'];
                }
                if ($data['payer_cpf_cnpj']) {
                    $update['payer_cpf_cnpj'] = $data['payer_cpf_cnpj'];
                }

                if ($data['order_id']) {
                    $update['order_id'] = $data['order_id'];
                }
                
                $update['rawBody'] = json_encode(['event' => $event, 'data' => $update]);



                $fatura_iugu->update($update);

                return response()->json(['error' => 'success'], 200);
            }
        }
    }
}
