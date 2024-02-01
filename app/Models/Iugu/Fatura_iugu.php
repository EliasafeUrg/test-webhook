<?php

namespace App\Models\Iugu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatura_iugu extends Model
{
    use HasFactory;
    protected $connection = "db_pagamentos_api";
    protected $table = 'fatura_iugu';
    protected $fillable = ['status_fatura','tipo_pagamento','payer_cpf_cnpj','order_id','rawBody'];

}
