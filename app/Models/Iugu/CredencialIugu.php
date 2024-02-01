<?php

namespace App\Models\Iugu;

use App\Models\Cedentes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CredencialIugu extends Model
{
    use HasFactory;
    protected $connection = "db_pagamentos_api";
    protected $table = 'credenciais_iugu';


    public function faturaIugu()
    {
        return $this->belongsTo(Fatura_iugu::class, 'id', 'credencial_iugu_id');
    }
    
    public function cedente()
    {
        return $this->belongsTo(Cedentes::class, 'cedente_id', 'id');
    }

}
