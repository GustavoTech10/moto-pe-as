<?php
include 'conexao.php';

$sql = "SELECT v.id, p.nome as produto, v.quantidade, v.total, v.vendedor, v.cliente, v.data 
		FROM vendas v 
		LEFT JOIN produtos p ON v.produto_id = p.id
		ORDER BY v.id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Vendas</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">GUSTAVO MOTOPEÇAS</div>
<div class="container">
	<div class="table-header">
		<h2>VENDAS</h2>
		<div>
			<a href="index.php"><button class="btn-voltar">⬅ Voltar</button></a>
			<a href="adicionar_venda.php"><button class="btn-add">Registrar Venda</button></a>
		</div>
	</div>

	<table>
	<tr>
	<th>ID</th>
	<th>Produto</th>
	<th>Quantidade</th>
	<th>Total</th>
	<th>Desconto</th>
	<th>Pagamento</th>
	<th>Vendedor</th>
	<th>Cliente</th>
	<th>Data</th>
	</tr>

	<?php if($result && $result->num_rows>0): ?>
	<?php while($row = $result->fetch_assoc()): ?>
	<tr>
	<td><?= $row['id'] ?></td>
	<td><?= htmlspecialchars($row['produto'] ?? '—') ?></td>
	<td><?= htmlspecialchars($row['quantidade'] ?? 0) ?></td>
	<td>R$ <?= number_format((float)($row['total'] ?? 0),2,',','.') ?></td>
	<td>R$ <?= number_format((float)($row['desconto'] ?? 0),2,',','.') ?></td>
	<td><?= htmlspecialchars(($row['pagamento'] ?? '') !== '' ? $row['pagamento'] : '—') ?></td>
	<td><?= htmlspecialchars($row['vendedor'] ?? '') ?></td>
	<td><?= htmlspecialchars($row['cliente'] ?? '') ?></td>
	<td><?= $row['data'] ?></td>
	</tr>
	<?php endwhile; ?>
	<?php else: ?>
	<tr><td colspan="7">Nenhuma venda registrada</td></tr>
	<?php endif; ?>

	</table>
</div>
</body>
</html>
<?php
// fim vendas.php
?>