<table id="tabela-producao-liquido">
  <thead>
    <tr>
      <th>MARCA</th>
      <th>FORMATO</th>
      <th>PRODUÇÃO (cx)</th>
      <th>PERDA CAIXA</th>
      <th>PERDA RÓTULO</th>
      <th>PERDA TAMPA</th>
      <th>PERDA GARRAFA</th>
    </tr>
  </thead>
  <tbody>
    <!-- Primeira linha será inserida via JS -->
  </tbody>
</table>

<button type="button" onclick="addLinhaLiquido()">+ Produto</button>

<script>
let linhaLiquidoCount = 0;
const formatosLiquido = ['0.5','1','1.5','2','2.5','3','5']; // formatos líquidos

function addLinhaLiquido() {
  const tbody = document.querySelector("#tabela-producao-liquido tbody");
  const tr = document.createElement("tr");

  // Radios de formato com o primeiro selecionado
  let tdFormato = "<div class='grid-formatos'>";
  formatosLiquido.forEach((f, index) => {
    tdFormato += `
      <input type="radio" id="formatoL${linhaLiquidoCount}_${f}" 
             name="formato_liq[${linhaLiquidoCount}]" value="${f}" ${index===0?'checked':''}>
      <label for="formatoL${linhaLiquidoCount}_${f}">${f}</label>
    `;
  });
  tdFormato += "</div>";

  tr.innerHTML = `
    <td><input type="text" name="marca_liq[${linhaLiquidoCount}]" placeholder="Marca do Produto" required></td>
    <td>${tdFormato}</td>
    <td><input type="text" name="producao[${linhaLiquidoCount}]"      placeholder="ex. 3"></td>
    <td><input type="text" name="perda_caixa[${linhaLiquidoCount}]"   placeholder="ex. 3"></td>
    <td><input type="text" name="perda_rotulo[${linhaLiquidoCount}]"  placeholder="ex. 3"></td>
    <td><input type="text" name="perda_tampa[${linhaLiquidoCount}]"   placeholder="ex. 5"></td>
    <td><input type="text" name="perda_garrafa[${linhaLiquidoCount}]" placeholder="ex. 0"></td>
  `;

  tbody.appendChild(tr);
  linhaLiquidoCount++;
}

// Adiciona a primeira linha ao carregar
addLinhaLiquido();

// --- Conversão de vírgula para ponto antes de enviar o form ---
document.addEventListener("submit", function(e) {
  const inputs = e.target.querySelectorAll("input[type='text']");
  inputs.forEach(input => {
    if (input.value.includes(",")) {
      // troca todas as vírgulas por ponto
      input.value = input.value.replace(/,/g, ".");
    }
  });
});
</script>

<style>
.grid-formatos {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
}
.grid-formatos label {
  font-size: 0.9em;
}
</style>
