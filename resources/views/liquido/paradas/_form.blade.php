<form action="{{ route('liquido.paradas.store') }}" method="POST">
@csrf

  <!-- DADOS GERAIS -->
  <div class="dados-gerais">
    <label>Linha:
      <div class="botoes1">
        <button type="button" class="botao-linha" onclick="selecionarGrupo(this, 'linha', 'linhaSelecionada')">1</button>
        <button type="button" class="botao-linha" onclick="selecionarGrupo(this, 'linha', 'linhaSelecionada')">2</button>
        <button type="button" class="botao-linha" onclick="selecionarGrupo(this, 'linha', 'linhaSelecionada')">4</button>
      </div>
      <input type="hidden" name="linha" id="linhaSelecionada" required>
    </label>

    <label>Turno:
      <div class="botoes1">
        <button type="button" class="botao-turno" onclick="selecionarGrupo(this, 'turno', 'turnoSelecionado')">1</button>
        <button type="button" class="botao-turno" onclick="selecionarGrupo(this, 'turno', 'turnoSelecionado')">2</button>
        <button type="button" class="botao-turno" onclick="selecionarGrupo(this, 'turno', 'turnoSelecionado')">3</button>
      </div>
      <input type="hidden" name="turno" id="turnoSelecionado" required>
    </label>

    <label>Unidade:
      <div class="botoes2">
        <button type="button" class="botao-unidade" onclick="selecionarGrupo(this, 'unidade', 'unidadeSelecionada')">LIQUIDO</button>
      </div>
      <input type="hidden" name="unidade" id="unidadeSelecionada" required>
    </label>

    <label>Operador: <input type="text" name="operador" required autocomplete="off"></label>
    <label>Data: <input type="date" name="data_registro" required autocomplete="off"></label>
  </div>

  <!-- TABELA DE PARADAS -->
  <table class="dados_corpo" id="tabelaParadas">
    <thead>
      <tr>
        <th>Início</th>
        <th>Fim</th>
        <th>Tempo (min)</th>
        <th>Código</th>
        <th>Descrição</th>
        <th>Observações</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><input type="datetime-local" name="inicio[]"></td>
        <td><input type="datetime-local" name="fim[]"></td>
        <td><input type="number" step="0.01" min="0" name="tempo[]" placeholder="ex. 15" readonly></td>
        <td><input type="text" name="codigo[]" class="codigo" data-index="0" autocomplete="off"></td>
        <td>
          <input type="text" name="descricao[]" class="descricao" data-index="0" autocomplete="off">
          <div class="sugestoes-descricao" data-index="0" style="position: relative; z-index: 1000;"></div>
        </td>
        <td><input type="text" name="observacoes[]" class="observacoes" autocomplete="off"></td>
      </tr>
    </tbody>
  </table>

  <!-- Inclui Velocidade e Produção/Perdas -->
  @include('liquido.paradas._velocidade')
  @include('liquido.paradas._producao')


  <div class="botoes">
    <button type="submit">Salvar Paradas</button>
  </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

  // ==============================
  // Seleção de Linha/Turno/Unidade
  // ==============================
  function selecionarGrupo(botao, grupo, idCampoHidden) {
    const botoes = botao.parentElement.querySelectorAll("button");
    botoes.forEach(btn => btn.classList.remove("selecionado"));
    botao.classList.add("selecionado");
    document.getElementById(idCampoHidden).value = botao.textContent.trim();

    if (idCampoHidden === "linhaSelecionada") {
      $(".codigo").each(function () { buscarPorCodigo($(this)); });
    }
  }
  window.selecionarGrupo = selecionarGrupo;

  function linhaSelecionada() { return $("#linhaSelecionada").val().trim(); }

  // ==============================
  // Código → Descrição
  // ==============================
  function buscarPorCodigo($campoCodigo) {
    const codigo = $campoCodigo.val().trim();
    const index = $campoCodigo.data("index");
    const descricaoInput = $(".descricao[data-index='" + index + "']");
    const linha = linhaSelecionada();
    if (codigo && linha) {
      $.get("../../enviar/enviar_liq/buscar_por_codigo_liq.php", { codigo: codigo, linha: linha }, function (data) {
        try { const resposta = JSON.parse(data); descricaoInput.val(resposta.descricao || ""); }
        catch (e) { descricaoInput.val(""); }
      });
    } else { descricaoInput.val(""); }
  }
  $(document).on("input blur change", ".codigo", function () { buscarPorCodigo($(this)); });

  // ==============================
  // Descrição → Código + Sugestões
  // ==============================
  $(document).on("input", ".descricao", function () {
    const descricao = $(this).val(), index = $(this).data("index");
    const codigoInput = $(".codigo[data-index='" + index + "']");
    const sugestoesDiv = $(".sugestoes-descricao[data-index='" + index + "']");
    const linha = linhaSelecionada();
    if (descricao.length > 0 && linha) {
      $.get("../../enviar/enviar_liq/buscar_liq.php", { term: descricao, linha: linha }, function (data) {
        try {
          const sugestoes = JSON.parse(data);
          let html = "", preenchido = false;
          sugestoes.forEach(function (item) {
            html += `<div class='sugestao' style='cursor:pointer; padding:4px; background:#eee; border-bottom:1px solid #ccc;' data-codigo='${item.codigo}' data-index='${index}'>${item.descricao}</div>`;
            if (item.descricao.toLowerCase() === descricao.toLowerCase() && !preenchido) { codigoInput.val(item.codigo); preenchido = true; }
          });
          if (html) { sugestoesDiv.html(html).show(); } else { sugestoesDiv.hide(); if (!preenchido) codigoInput.val(""); }
        } catch(e) { sugestoesDiv.hide(); codigoInput.val(""); }
      });
    } else { sugestoesDiv.hide(); codigoInput.val(""); }
  });

  // ==============================
  // Clique na sugestão
  // ==============================
  $(document).on("click", ".sugestao", function () {
    const index = $(this).data("index");
    const descricao = $(this).text();
    const codigo = $(this).data("codigo");
    $(".descricao[data-index='" + index + "']").val(descricao);
    $(".codigo[data-index='" + index + "']").val(codigo);
    $(".sugestoes-descricao[data-index='" + index + "']").hide();
  });

  // ==============================
  // Pressionar seta para baixo → nova linha
  // ==============================
  $(document).on("keydown", ".observacoes", function (e) {
    if (e.key === "ArrowDown") {
      const todasObs = $(".observacoes");
      const ultimaObs = todasObs.last()[0];
      if (this === ultimaObs) { e.preventDefault(); adicionarNovaLinha(todasObs.length); }
    }
  });

  // ==============================
  // Adicionar nova linha
  // ==============================
  function adicionarNovaLinha(index) {
    const novaLinha = `
      <tr>
        <td><input type="datetime-local" name="inicio[]"></td>
        <td><input type="datetime-local" name="fim[]"></td>
        <td><input type="number" step="0.01" min="0" name="tempo[]" placeholder="ex. 15" readonly></td>
        <td><input type="text" class="codigo" name="codigo[]" data-index="${index}" autocomplete="off"></td>
        <td>
          <input type="text" class="descricao" name="descricao[]" data-index="${index}" autocomplete="off">
          <div class="sugestoes-descricao" data-index="${index}" style="position: relative; z-index: 1000;"></div>
        </td>
        <td><input type="text" class="observacoes" name="observacoes[]"></td>
      </tr>`;
    $("#tabelaParadas tbody").append(novaLinha);
    $("#tabelaParadas tbody tr:last input[name='inicio[]']").focus();
  }

  // ==============================
  // Cálculo automático do tempo (readonly)
  // ==============================
  $(document).on('change', "input[name='inicio[]'], input[name='fim[]']", function() {
    const tr = $(this).closest('tr');
    const inicio = new Date(tr.find("input[name='inicio[]']").val());
    const fim = new Date(tr.find("input[name='fim[]']").val());
    if (inicio && fim && fim > inicio) {
      const diff = (fim - inicio) / 60000; // minutos
      tr.find("input[name='tempo[]']").val(diff.toFixed(2));
    } else {
      tr.find("input[name='tempo[]']").val('');
    }
  });

});
</script>
