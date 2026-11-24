<?php
namespace App\Http\Requests\Liquido;
use Illuminate\Foundation\Http\FormRequest;

class StoreParadaLiquidoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Dados gerais
            'linha' => 'required|in:1,2,4',
            'turno' => 'required|in:1,2,3',
            'operador' => 'required|string|max:100',
            'data_registro' => 'required|date',

            // Paradas múltiplas
            'paradas.inicio' => 'required|array|min:1',
            'paradas.inicio.*' => 'required|date',
            'paradas.fim' => 'nullable|array',
            'paradas.fim.*' => 'nullable|date|after_or_equal:paradas.inicio.*',
            'paradas.codigo' => 'required|array',
            'paradas.codigo.*' => 'required|string|max:10',
            'paradas.descricao' => 'required|array',
            'paradas.descricao.*' => 'required|string|max:255',
            'paradas.tempo' => 'required|array',
            'paradas.tempo.*' => 'numeric|min:0',
            'paradas.observacoes' => 'nullable|array',
            'paradas.observacoes.*' => 'nullable|string|max:500',

            // Velocidade (9 campos)
            'velocidade.1' => 'nullable|numeric|min:0',
            'velocidade.2' => 'nullable|numeric|min:0',
            'velocidade.3' => 'nullable|numeric|min:0',
            'velocidade.4' => 'nullable|numeric|min:0',
            'velocidade.5' => 'nullable|numeric|min:0',
            'velocidade.6' => 'nullable|numeric|min:0',
            'velocidade.7' => 'nullable|numeric|min:0',
            'velocidade.8' => 'nullable|numeric|min:0',
            'velocidade.9' => 'nullable|numeric|min:0',

            // Produção dinâmica
            'producao.marca' => 'required|array|min:1',
            'producao.marca.*' => 'required|string|max:100',
            'producao.formato' => 'required|array',
            'producao.formato.*' => 'required|in:0.5,1,1.5,2,2.5,3,5',
            'producao.producao' => 'required|array',
            'producao.producao.*' => 'nullable|numeric|min:0',
            'producao.perda_caixa' => 'nullable|array',
            'producao.perda_caixa.*' => 'nullable|numeric|min:0',
            'producao.perda_rotulo' => 'nullable|array',
            'producao.perda_rotulo.*' => 'nullable|numeric|min:0',
            'producao.perda_tampa' => 'nullable|array',
            'producao.perda_tampa.*' => 'nullable|numeric|min:0',
            'producao.perda_garrafa' => 'nullable|array',
            'producao.perda_garrafa.*' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'linha.required' => 'Selecione a linha!',
            'turno.required' => 'Selecione o turno!',
            'operador.required' => 'Informe o operador!',
            'paradas.inicio.*.required' => 'Data de início é obrigatória!',
            'paradas.codigo.*.required' => 'Código é obrigatório!',
        ];
    }
}
