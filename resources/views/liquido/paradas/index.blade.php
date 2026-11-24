<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Paradas Líquido</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<div class="formulario">

<h2>FEV 0012 REGISTRO DE PRODUÇÃO E PARADAS-LIQUIDO</h2>

<form method="POST" action="{{ route('liquido.paradas.store') }}">
@csrf

<!-- DADOS GERAIS -->
<div class="dados-gerais">

<label>Linha
    <input type="text" name="linha">
</label>

<label>Turno
    <input type="text" name="turno">
</label>

<label>Operador
    <input type="text" name="operador">
</label>

<label>Data
    <input type="date" name="data_registro">
</label>

</div>


<table>
<thead>
<tr>
<th>Início</th>
<th>Fim</th>
<th>Tempo</th>
<th>Código</th>
<th>Descrição</th>
<th>Observações</th>
</tr>
</thead>

<tbody>

<tr>
<td><input type="datetime-local" name="paradas[inicio][]"></td>
<td><input type="datetime-local" name="paradas[fim][]"></td>
<td><input type="number" name="paradas[tempo][]"></td>
<td><input type="text" name="paradas[codigo][]"></td>
<td><input type="text" name="paradas[descricao][]"></td>
<td><input type="text" name="paradas[observacoes][]"></td>
</tr>

</tbody>
</table>

<button type="submit">Salvar</button>

</form>

</div>

</body>
</html>
