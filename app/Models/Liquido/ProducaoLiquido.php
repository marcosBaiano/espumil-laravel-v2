<?php
namespace App\Models\liquido;
use Illuminate\Database\Eloquent\Model;

class ProducaoLiquido extends Model
{
    protected $table = 'producao_liquido'; // Crie esta tabela depois
    protected $fillable = ['parada_id', 'marca', 'formato', 'producao', 'perda_caixa', 'perda_rotulo', 'perda_tampa', 'perda_garrafa'];
}
