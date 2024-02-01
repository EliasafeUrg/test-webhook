<?php

namespace App\Console\Commands;

use App\Models\Iugu\CredencialIugu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class webhookTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:webhook-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // $credencial_iugu = CredencialIugu::with('faturaIugu')->where('id_cliente_iugu', '7E308EFA5A774958BA83FFE9DF0FF59B')->first();
        $iugu_webhook_token  = config('app.iugu_webhook_token');
        $basicAuth  = base64_encode($iugu_webhook_token);

        Http::withHeaders([
            'Authorization' => "Basic $basicAuth",
            'accept' => 'application/json',
            'content-type: application/json'
          ])->post('http://127.0.0.1:8000/api/webhook/iugu', [
            'event' => 'invoice.status_changed',
            'data' => [
                'id' => 'E3EF2123DD01406F959D6081ED678041',
                'account_id' => '59D6DF4FCD004F08889B0C231693A3BA',
                'status' => 'paid',
                'payment_method' => 'bank_slip',
                'payer_cpf_cnpj' => '66535209008',
                'order_id' => 'N77579_31658163'
            ],
        ]);


    }
}
