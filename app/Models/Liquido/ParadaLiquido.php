<?php

namespace App\Models\liquido;

use Illuminate\Database\Eloquent\Model;

class ParadaLiquido extends Model
{
    protected $table = 'paradas_liquido';

    public $timestamps = false;

   protected $fillable = [
    'data_registro',
    'linha',
    'turno',
    'unidade',
    'operador',
    'inicio',
    'fim',
    'codigo',
    'descricao',
    'observacoes',
    'tempo'
];

}
