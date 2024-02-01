<?php

namespace App\Models\DenteFacil;

use App\Models\Iugu\Fatura_iugu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faturas extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'faturas';
    protected $connection = 'mysql';
    protected $fillable = [
        'parcela_atual',
        'pago',
        'valor_parcela',
        'valor_fatura',
        'num_boleto',
    ];

    public function getValorParcelaAttribute($value)
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
    public function getNumBoletoAttribute($value)
    {
        return  preg_replace('/[^0-9]/', '', $value);
    }
    public function getValorFaturaAttribute($value)
    {

        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    public function getValorFatura()
    {
        return $this->attributes['valor_fatura'] ?? null;
    }

    public function getValorParcela()
    {
        return $this->attributes['valor_parcela'];
    }


    public function setNumBoletoAttribute($value)
    {
        // Remove todos os caracteres não numéricos (exceto dígitos)
        $numeroLimpo = preg_replace('/[^0-9]/', '', $value);

        // Formatação do número do boleto
        $numeroFormatado = chunk_split($numeroLimpo, 5, '.');
        $numeroFormatado = substr($numeroFormatado, 0, -1);
        $this->attributes['num_boleto'] = str_replace(' ', ' ', $numeroFormatado);

    }

    public function setValorParcelaAttribute($value)
    {
        $this->attributes['valor_parcela'] = (float)str_replace(['R$', '.', ','], ['', '', '.'], $value);
    }
    public function setValorFaturaAttribute($value)
    {
        $this->attributes['valor_fatura'] = (float)str_replace(['R$', '.', ','], ['', '', '.'], $value);
    }

    public function os_det()
    {
        return $this->hasOne(OrdemServicoDetalhe::class, 'id', 'det_id');
    }

    public function fatura_iugu()
    {
        return $this->hasOne(Fatura_iugu::class, 'id', 'dentefacil_fatura_iugu_id');
    }


    public function hasActiveParcelamento()
    {
        return $this->parcelamento()->where('pago', true)->exists();
    }
    public function hasParcelamento()
    {
        return $this->parcelamento();
    }

    public function parcelamento()
    {
        return $this->hasMany(Parcelas::class, 'fatura_id', 'id');
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'id', 'paciente_id');
    }

    public function formaPagamento()
    {
        return $this->hasOne(FormasPagamento::class, 'id', 'forma_pagamento_id');
    }
    // public function os(){
    //     return $this->belongsTo(OrdemServico::class,'paciente_id','paciente_id');
    // }

    public function os()
    {
        return $this->hasOne(OrdemServico::class, 'id', 'os_id');
    }
    public function faturasDetalhe()
    {
        return $this->hasMany(FaturasDetalhe::class, 'fatura_id', 'id');
    }
}
