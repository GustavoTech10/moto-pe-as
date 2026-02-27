<?php
include 'conexao.php';

// Verificar se há funcionário com cargo "Financeiro"
$check_financeiro = $conn->query("SELECT COUNT(*) as total FROM funcionarios WHERE cargo='Financeiro'");
$tem_financeiro = $check_financeiro->fetch_assoc()['total'] > 0;

// Se não houver Financeiro, criar automaticamente
if (!$tem_financeiro) {
    $nome_padrao = "Sistema - Financeiro";
    $cargo = "Financeiro";
    $salario = 0;
    $data_admissao = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO funcionarios (nome, cargo, salario, data_admissao) VALUES (?,?,?,?)");
    $stmt->bind_param("ssds", $nome_padrao, $cargo, $salario, $data_admissao);
    $stmt->execute();
    $stmt->close();
}

// filtro opcional por data
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';
$where = "";
$params = [];
if ($from && $to) {
    $where = "WHERE data BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
}

$sql = "SELECT * FROM caixa $where ORDER BY data DESC";
$result = $conn->query($sql);

// soma total
$sqlSum = "SELECT SUM(valor) as total FROM caixa $where";
$resSum = $conn->query($sqlSum);
$total = 0;
if ($resSum && $rowS = $resSum->fetch_assoc())
    $total = $rowS['total'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Relatório do Caixa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .restricted-access {
            background: #8B0000;
            color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid yellow;
        }

        .restricted-access strong {
            color: #ffff00;
        }
    </style>
</head>

<body>
    <div class="navbar">GUSTAVO MOTOPEÇAS</div>
    <div class="container">
        <div class="restricted-access">
            <strong>🔒 ACESSO RESTRITO:</strong> Este relatório é exclusivo para funcionários com cargo
            <strong>FINANCEIRO</strong>
        </div>

        <div class="table-header">
            <h2>CAIXA</h2>
            <div>
                <a href="index.php"><button class="btn-voltar">Voltar</button></a>
            </div>
        </div>

        <form method="GET" style="margin-bottom:15px;">
            <label>Funcionário Financeiro</label>
            <select name="financeiro_id"
                style="width:200px;padding:8px;background:#000;color:red;border:1px solid red;">
                <?php
                $financeiros = $conn->query("SELECT id, nome FROM funcionarios WHERE cargo='Financeiro' ORDER BY nome");
                if ($financeiros && $financeiros->num_rows > 0) {
                    while ($f = $financeiros->fetch_assoc()) {
                        echo '<option value="' . $f['id'] . '">' . $f['nome'] . '</option>';
                    }
                }
                ?>
            </select>

            <label>De</label>
            <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
            <label>Até</label>
            <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
            <button class="btn-add" type="submit">Filtrar</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Forma</th>
                <th>Vendedor</th>
                <th>Cliente</th>
                <th>Data</th>
            </tr>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['descricao']) ?></td>
                        <td>R$ <?= number_format((float) $row['valor'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['forma_pagamento'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['vendedor'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['cliente'] ?? '') ?></td>
                        <td><?= $row['data'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Nenhuma entrada no caixa</td>
                </tr>
            <?php endif; ?>

        </table>

        <div style="margin-top:15px; font-weight:bold;">Saldo total: R$ <?= number_format((float) $total, 2, ',', '.') ?>
        </div>

    </div>
</body>

</html>