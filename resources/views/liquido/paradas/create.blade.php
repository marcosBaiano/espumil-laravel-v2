@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-tint"></i> Registro Completo - Líquido</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('liquido.paradas.store') }}" method="POST" id="formCompleto">
                        @csrf
                        
                        {{-- DADOS GERAIS --}}
                        <div class="row mb-4 p-3 bg-light rounded">
                            <div class="col-md-2">
                                <label>Linha:</label>
                                <div class="botoes1">
                                    <button type="button" class="botao-linha @if(old('linha',1)==1) selecionado @endif" onclick="selecionarGrupo(this, 'linha', 'linhaSelecionada')" value="1">1</button>
                                    <button type="button" class="botao-linha @if(old('linha')==2) selecionado @endif" onclick="selecionarGrupo(this, 'linha', 'linhaSelecionada')" value="2">2</button>
                                    <button type="button" class="botao-linha @if(old('linha')==4) selecionado @endif" onclick="selecionarGrupo(this, 'linha', 'linhaSelecionada')" value="4">4</button>
                                </div>
                                <input type="hidden" name="linha" id="linhaSelecionada" value="{{ old('linha',1) }}" required>
                            </div>
                            
                            <div class="col-md-2">
                                <label>Turno:</label>
                                <div class="botoes1">
                                    <button type="button" class="botao-turno @if(old('turno',1)==1) selecionado @endif" onclick="selecionarGrupo(this, 'turno', 'turnoSelecionado')" value="1">1</button>
                                    <button type="button" class="botao-turno @if(old('turno')==2) selecionado @endif" onclick="selecionarGrupo(this, 'turno', 'turnoSelecionado')" value="2">2</button>
                                    <button type="button" class="botao-turno @if(old('turno')==3) selecionado @endif" onclick="selecionarGrupo(this, 'turno', 'turnoSelecionado')" value="3">3</button>
                                </div>
                                <input type="hidden" name="turno" id="turnoSelecionada" value="{{ old('turno',1) }}" required>
                            </div>
                            
                            <div class="col-md-2">
                                <label>Operador:</label>
                                <input type="text" name="operador" value="{{ old('operador') }}" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label>Data:</label>
                                <input type="date" name="data_registro" value="{{ old('data_registro') }}" class="form-control" required>
                            </div>
                        </div>

                        {{-- TABELA PARADAS --}}
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered" id="tabelaParadas">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Início</th><th>Fim</th><th>Tempo (min)</th><th>Código</th><th>Descrição</th><th>Observações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="datetime-local" name="paradas[inicio][]" class="form-control inicio"></td>
                                        <td><input type="datetime-local" name="paradas[fim][]" class="form-control fim"></td>
                                        <td><input type="number" step="0.01" name="paradas[tempo][]" class="form-control tempo" readonly></td>
                                        <td><input type="text" name="paradas[codigo][]" class="form-control codigo" data-index="0"></td>
                                        <td>
                                            <input type="text" name="paradas[descricao][]" class="form-control descricao" data-index="0">
                                            <div class="sugestoes-descricao position-relative" data-index="0"></div>
                                        </td>
                                        <td><input type="text" name="paradas[observacoes][]" class="form-control observacoes"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm" onclick="adicionarNovaLinha()"><i class="fas fa-plus"></i> Nova Parada</button>
                        </div>

                        {{-- VELOCIDADE --}}
                        <div class="card mb-4">
                            <div class="card-header">Velocidade da Máquina (a cada 1h)</div>
                            <div class="card-body">
                                <div class="row">
                                    @for($i=1; $i<=9; $i++)
                                    <div class="col-1">
                                        <input type="number" name="velocidade[{{$i}}]" class="form-control form-control-sm" autocomplete="off" placeholder="{{ $i }}">
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        {{-- PRODUÇÃO --}}
                        <div class="card mb-4">
                            <div class="card-header">Produção e Perdas</div>
                            <div class="card-body">
                                <table class="table table-bordered" id="tabelaProducao">
                                    <thead>
                                        <tr><th>Marca</th><th>Formato</th><th>Produção(cx)</th><th>Perda Caixa</th><th>Perda Rótulo</th><th>Perda Tampa</th><th>Perda Garrafa</th></tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addLinhaProducao()"><i class="fas fa-plus"></i> + Produto</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg btn-success">
                            <i class="fas fa-save"></i> Salvar Registro Completo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let linhaCount = $('#tabelaParadas tbody tr').length;
    
    // SEU CÓDIGO ORIGINAL - Seleção linha/turno
    window.selecionarGrupo = function(btn, grupo, idHidden) {
        $(btn).parent().find('button').removeClass('selecionado');
        $(btn).addClass('selecionado');
        $('#' + idHidden).val($(btn).textContent || $(btn).val());
        
        // Recarrega autocomplete quando linha muda
        $('.codigo').each(function() { buscarPorCodigo($(this)); });
    };

    function linhaSelecionada() { 
        return $('#linhaSelecionada').val().trim() || 1; 
    }

    // ========== CÓDIGO → DESCRIÇÃO (SEU CÓDIGO) ==========
    $(document).on('input blur change', '.codigo', function() {
        buscarPorCodigo($(this));
    });

    function buscarPorCodigo($campoCodigo) {
        const codigo = $campoCodigo.val().trim();
        const index = $campoCodigo.data('index');
        const descricaoInput = $(`.descricao[data-index='${index}']`);
        const linha = linhaSelecionada();
        
        if (codigo && linha) {
            $.get(`/liquido/paradas/descricao/${codigo}?linha=${linha}`, function(data) {
                if (data.descricao) {
                    descricaoInput.val(data.descricao);
                }
            });
        }
    }

    // ========== DESCRIÇÃO → SUGESTÕES (SEU CÓDIGO) ==========
    $(document).on('input', '.descricao', function() {
        const descricao = $(this).val().trim();
        const index = $(this).data('index');
        const codigoInput = $(`.codigo[data-index='${index}']`);
        const sugestoesDiv = $(`.sugestoes-descricao[data-index='${index}']`);
        const linha = linhaSelecionada();
        
        if (descricao.length > 2 && linha) {
            $.get(`/liquido/paradas/buscar/${encodeURIComponent(descricao)}?linha=${linha}`, function(sugestoes) {
                let html = '';
                sugestoes.forEach(item => {
                    html += `<div class='sugestao p-2 bg-light border-bottom sugestoes-item' 
                                data-codigo='${item.codigo}' data-index='${index}' 
                                style='cursor:pointer;'>${item.descricao}</div>`;
                });
                
                if (html) {
                    sugestoesDiv.html(html).show();
                } else {
                    sugestoesDiv.hide();
                }
            });
        } else {
            sugestoesDiv.hide();
        }
    });

    // Clique na sugestão (SEU CÓDIGO)
    $(document).on('click', '.sugestao', function() {
        const index = $(this).data('index');
        const descricao = $(this).text().trim();
        const codigo = $(this).data('codigo');
        
        $(`.descricao[data-index='${index}']`).val(descricao);
        $(`.codigo[data-index='${index}']`).val(codigo);
        $(`.sugestoes-descricao[data-index='${index}']`).hide();
    });

    // ESC para fechar sugestões
    $(document).on('keydown', '.descricao', function(e) {
        if (e.key === 'Escape') {
            $(`.sugestoes-descricao[data-index='${$(this).data('index')}']`).hide();
        }
    });

    // SEUS OUTROS CÓDIGOS (mantidos)
    // Cálculo tempo
    $(document).on('change', '.inicio, .fim', function() {
        const tr = $(this).closest('tr');
        const inicio = new Date(tr.find('.inicio').val());
        const fim = new Date(tr.find('.fim').val());
        if (inicio && fim && fim > inicio) {
            tr.find('.tempo').val(((fim - inicio) / 60000).toFixed(2));
        }
    });

    // Conversão vírgula → ponto
    $('form').on('submit', function() {
        $(this).find('input[type="text"]').each(function() {
            if ($(this).val().includes(',')) {
                $(this).val($(this).val().replace(/,/g, '.'));
            }
        });
    });
});
</script>

<style>
.sugestoes-descricao {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 200px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    z-index: 1050;
    display: none;
}
.sugestao:hover { background: #e9ecef !important; }
</style>


<style>
.botoes1 { display: flex; gap: 5px; }
.botao-linha, .botao-turno { 
    padding: 8px 12px; border: 2px solid #ddd; background: white; 
    cursor: pointer; border-radius: 4px; 
}
.botao-linha.selecionado, .botao-turno.selecionado { 
    background: #007bff !important; color: white; border-color: #007bff;
}
.grid-formatos { display: flex; flex-wrap: wrap; gap: 4px; }
.grid-formatos label { font-size: 0.9em; cursor: pointer; }
</style>
@endsection
