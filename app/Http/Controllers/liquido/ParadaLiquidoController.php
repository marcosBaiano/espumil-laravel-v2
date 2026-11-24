<?php

namespace App\Http\Controllers\Liquido;

use App\Http\Controllers\Controller;
use App\Http\Requests\Liquido\StoreParadaLiquidoRequest;
use App\Http\Requests\Liquido\UpdateParadaLiquidoRequest;
use App\Models\liquido\ParadaLiquido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParadaLiquidoController extends Controller
{
    public function index()
    {
        $paradas = ParadaLiquido::orderBy('id', 'desc')->take(20)->get();
        return view('liquido.paradas.index', compact('paradas'));
    }

    public function create()
    {
        return view('liquido.paradas.create');
    }

    public function store(StoreParadaLiquidoRequest $request)
{
    DB::beginTransaction();

    try {

        $paradaIds = [];

        foreach ($request->inicio as $i => $inicio) {

            // ignora linha vazia
            if (!$inicio) {
                continue;
            }

            $parada = ParadaLiquido::create([
                'data_registro' => $request->data ?? now()->toDateString(),

                'linha'     => $request->linha,
                'turno'     => $request->turno,
                'unidade'   => $request->unidade,
                'operador'  => $request->operador,

                'inicio'    => $request->inicio[$i],
                'fim'       => $request->fim[$i],
                'codigo'    => $request->codigo[$i] ?? null,
                'descricao' => $request->descricao[$i] ?? null,
                'observacoes' => $request->observacoes[$i] ?? null,
                'tempo'     => $request->tempo[$i] ?? null,
            ]);

            $paradaIds[] = $parada->id;
        }

        DB::commit();

        return redirect()->route('liquido.paradas.index')
            ->with('success', count($paradaIds) . ' parada(s) salva(s)!');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withErrors(['error' => $e->getMessage()])
            ->withInput();
    }
}


    public function destroy(ParadaLiquido $parada)
    {
        DB::beginTransaction();
        try {
            $parada->delete();
            DB::commit();
            return redirect()->route('liquido.paradas.index')
                           ->with('success', 'Parada excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao excluir: ' . $e->getMessage()]);
    
            }
    }
 
                // Buscar descrição por código
        public function getDescricaoByCodigo($codigo)
        {
            $linha = request('linha', 1);
            $descricao = DB::table('descricoes_liq')
                ->where('codigo', $codigo)
                ->where('linha', $linha)
                ->first();

            return response()->json($descricao ?? ['descricao' => '']);
        }

        // Buscar sugestões por descrição
        public function buscarSugestoes($termo)
        {
            $linha = request('linha', 1);
            $sugestoes = DB::table('descricoes_liq')
                ->where('linha', $linha)
                ->where('descricao', 'LIKE', "%{$termo}%")
                ->limit(5)
                ->get();

            return response()->json($sugestoes);
    }

}
