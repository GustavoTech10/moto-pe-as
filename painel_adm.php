<?php
include 'conexao.php';

// Verificar se existe pelo menos um Gestor
$check_gestor = $conn->query("SELECT COUNT(*) as total FROM funcionarios WHERE cargo='Gestor'");
$tem_gestor = $check_gestor->fetch_assoc()['total'] > 0;

if(!$tem_gestor){
    die('<div style="text-align:center;padding:50px;color:red;">Acesso negado. Apenas Gestores podem acessar este painel.</div>');
}

// MÉTRICAS ADMINISTRATIVAS
$total_funcionarios = $conn->query("SELECT COUNT(*) as total FROM funcionarios")->fetch_assoc()['total'];
$total_vendas = $conn->query("SELECT COUNT(*) as total FROM vendas")->fetch_assoc()['total'];
$receita_total = $conn->query("SELECT IFNULL(SUM(total),0) as soma FROM vendas")->fetch_assoc()['soma'];
$desconto_total = $conn->query("SELECT IFNULL(SUM(desconto),0) as soma FROM vendas")->fetch_assoc()['soma'];
$folha_salarios = $conn->query("SELECT IFNULL(SUM(salario),0) as soma FROM funcionarios")->fetch_assoc()['soma'];

// Produtos mais vendidos
$top_produtos = $conn->query("SELECT p.nome, SUM(v.quantidade) as total_vendido FROM vendas v JOIN produtos p ON v.produto_id = p.id GROUP BY p.id ORDER BY total_vendido DESC LIMIT 5");

// Clientes com mais compras
$top_clientes = $conn->query("SELECT cliente, COUNT(*) as num_compras, SUM(total) as total_gasto FROM vendas GROUP BY cliente ORDER BY total_gasto DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-header { background: #ff9500; color: black; padding: 20px; text-align: center; font-weight: bold; font-size: 18px; margin-bottom: 20px; border-radius: 8px; }
        .admin-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 30px; }
        .admin-card { background: #222; border: 2px solid #ff9500; padding: 20px; border-radius: 8px; text-align: center; }
        .admin-card h3 { color: #ff9500; margin: 0 0 10px 0; }
        .admin-card p { margin: 0; font-size: 24px; font-weight: bold; color: #fff; }
        .admin-section { background: #111; padding: 20px; margin-bottom: 20px; border-radius: 8px; border-left: 4px solid #ff9500; }
        .admin-section h2 { color: #ff9500; margin-top: 0; }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .admin-table th { background: #1a1a1a; color: #ff9500; padding: 12px; text-align: left; border-bottom: 2px solid #ff9500; }
        .admin-table td { padding: 12px; border-bottom: 1px solid #333; }
        .admin-table tr:hover { background: #1a1a1a; }
    </style>
</head>
<body>
<div class="navbar">GUSTAVO MOTOPEÇAS - PAINEL ADM</div>
<div class="container">
    <div class="admin-header">🔒 PAINEL ADMINISTRATIVO - ACESSO RESTRITO A GESTORES</div>

    <a href="index.php"><button class="btn-voltar">⬅ Voltar</button></a>

    <div class="admin-cards">
        <div class="admin-card">
            <h3>Funcionários</h3>
            <p><?= $total_funcionarios ?></p>
        </div>
        <div class="admin-card">
            <h3>Total Vendas</h3>
            <p><?= $total_vendas ?></p>
        </div>
        <div class="admin-card">
            <h3>Receita Total</h3>
            <p>R$ <?= number_format($receita_total, 2, ',', '.') ?></p>
        </div>
        <div class="admin-card">
            <h3>Descontos</h3>
            <p>R$ <?= number_format($desconto_total, 2, ',', '.') ?></p>
        </div>
        <div class="admin-card">
            <h3>Folha Salários</h3>
            <p>R$ <?= number_format($folha_salarios, 2, ',', '.') ?></p>
        </div>
        <div class="admin-card">
            <h3>Lucro Líquido</h3>
            <p>R$ <?= number_format($receita_total - $desconto_total - $folha_salarios, 2, ',', '.') ?></p>
        </div>
    </div>

    <div class="admin-section">
        <h2>📊 Produtos Mais Vendidos</h2>
        <table class="admin-table">
            <tr>
                <th>Produto</th>
                <th>Quantidade Vendida</th>
            </tr>
            <?php 
            if($top_produtos && $top_produtos->num_rows > 0){
                while($row = $top_produtos->fetch_assoc()){
                    echo '<tr><td>' . htmlspecialchars($row['nome']) . '</td><td>' . intval($row['total_vendido']) . ' unid</td></tr>';
                }
            } else {
                echo '<tr><td colspan="2">Nenhuma venda registrada</td></tr>';
            }
            ?>
        </table>
    </div>

    <div class="admin-section">
        <h2>👥 Clientes com Mais Compras</h2>
        <table class="admin-table">
            <tr>
                <th>Cliente</th>
                <th>Compras</th>
                <th>Total Gasto</th>
            </tr>
            <?php 
            if($top_clientes && $top_clientes->num_rows > 0){
                while($row = $top_clientes->fetch_assoc()){
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['cliente'] ?? '') . '</td>';
                    echo '<td>' . intval($row['num_compras']) . '</td>';
                    echo '<td>R$ ' . number_format($row['total_gasto'], 2, ',', '.') . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3">Nenhuma venda registrada</td></tr>';
            }
            ?>
        </table>
    </div>

    <div class="admin-section">
        <h2>⚙️ Funções Administrativas</h2>
        <p style="margin: 15px 0;">
            <a href="funcionarios.php"><button class="btn-add">Gerenciar Funcionários</button></a>
            <a href="clientes.php"><button class="btn-add">Gerenciar Clientes</button></a>
            <a href="fornecedores.php"><button class="btn-add">Gerenciar Fornecedores</button></a>
            <a href="caixa.php"><button class="btn-add">Relatório Caixa</button></a>
        </p>
    </div>

</div>
</body>
</html>