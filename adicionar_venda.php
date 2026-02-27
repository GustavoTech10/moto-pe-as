<?php
include 'conexao.php';

// obter apenas vendedores
$vendedores_res = $conn->query("SELECT id, nome FROM funcionarios WHERE cargo='Vendedor'");

// obter produtos e clientes para seleção
$produtos_res = $conn->query("SELECT id, nome, preco, estoque FROM produtos WHERE estoque>0");
$clientes_res = $conn->query("SELECT id, nome FROM clientes");

if (isset($_POST['produto_id'])) {
    $produto_id = intval($_POST['produto_id']);
    $quantidade = intval($_POST['quantidade']);
    $vendedor_id = intval($_POST['vendedor_id']);
    $cliente = $conn->real_escape_string($_POST['cliente']);
    $desconto = isset($_POST['desconto']) ? floatval($_POST['desconto']) : 0;
    $pagamento = isset($_POST['pagamento']) ? $conn->real_escape_string($_POST['pagamento']) : '';

    // validar que o vendedor tem cargo "Vendedor"
    $check_vendedor = $conn->query("SELECT nome FROM funcionarios WHERE id=$vendedor_id AND cargo='Vendedor'");
    if (!$check_vendedor || $check_vendedor->num_rows == 0) {
        echo "<script>alert('Apenas vendedores podem fazer vendas!'); window.history.back();</script>";
        exit;
    }
    $vendedor_row = $check_vendedor->fetch_assoc();
    $vendedor = $vendedor_row['nome'];

    // garantir colunas mínimas na tabela `vendas` (não sobrescreve se já existirem)
    $required = [
        'produto_id' => "INT NOT NULL",
        'quantidade' => "INT NOT NULL DEFAULT 0",
        'total' => "DECIMAL(10,2) NOT NULL DEFAULT 0",
        'desconto' => "DECIMAL(10,2) DEFAULT 0",
        'pagamento' => "VARCHAR(50)",
        'vendedor' => "VARCHAR(255)",
        'cliente' => "VARCHAR(255)",
        'data' => "DATETIME DEFAULT CURRENT_TIMESTAMP"
    ];
    $colsRes = $conn->query("SHOW COLUMNS FROM vendas");
    $existing = [];
    if ($colsRes) {
        while ($c = $colsRes->fetch_assoc()) {
            $existing[$c['Field']] = true;
        }
    }
    foreach ($required as $col => $def) {
        if (!isset($existing[$col])) {
            // Tenta adicionar a coluna sem interromper se não for possível
            $conn->query("ALTER TABLE vendas ADD COLUMN $col $def");
        }
    }

    // buscar preço e estoque atual
    $stmt = $conn->prepare("SELECT preco, estoque FROM produtos WHERE id=?");
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    $stmt->bind_result($preco, $estoque);
    if ($stmt->fetch()) {
        if ($quantidade <= 0 || $quantidade > $estoque) {
            echo "<script>alert('Quantidade inválida ou maior que o estoque.'); window.history.back();</script>";
            exit;
        }
        $subtotal = $preco * $quantidade;
        if ($desconto < 0)
            $desconto = 0;
        if ($desconto > $subtotal)
            $desconto = $subtotal;
        $total = $subtotal - $desconto;
        $stmt->close();

        // iniciar transação
        $conn->begin_transaction();
        try {
            // atualizar estoque
            $upd = $conn->prepare("UPDATE produtos SET estoque=estoque-? WHERE id=?");
            $upd->bind_param("ii", $quantidade, $produto_id);
            $upd->execute();
            $upd->close();

            // inserir venda
            $insVenda = $conn->prepare("INSERT INTO vendas (produto_id, quantidade, total, desconto, pagamento, vendedor, cliente) VALUES (?,?,?,?,?,?,?)");
            $insVenda->bind_param("iiddsss", $produto_id, $quantidade, $total, $desconto, $pagamento, $vendedor, $cliente);
            $insVenda->execute();
            $insVenda->close();

            // inserir no caixa
            $insCaixa = $conn->prepare("INSERT INTO caixa (descricao, valor, forma_pagamento, vendedor, cliente) VALUES ('Venda',?,?,?,?)");
            $insCaixa->bind_param("dsss", $total, $pagamento, $vendedor, $cliente);
            $insCaixa->execute();
            $insCaixa->close();

            $conn->commit();
            header("Location: vendas.php");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            echo "Erro ao processar venda: " . $e->getMessage();
            exit;
        }
    } else {
        echo "Produto não encontrado.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registrar Venda</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="navbar">GUSTAVO MOTOPEÇAS</div>
    <div class="container">
        <div class="card-editar">
            <h1>Registrar Venda</h1>
            <?php if (!$vendedores_res || $vendedores_res->num_rows == 0): ?>
                <div
                    style="background:#220000;color:red;padding:15px;margin-bottom:15px;border-radius:6px;text-align:center;">
                    <strong>⚠️ Nenhum vendedor cadastrado!</strong><br>
                    É necessário cadastrar funcionários com cargo "Vendedor" para fazer vendas.
                </div>
                <div class="voltar"><a href="index.php">⬅ Voltar</a></div>
            <?php else: ?>
                <form method="POST">
                    <label>Produto</label>
                    <select name="produto_id" required>
                        <option value="">-- escolha --</option>
                        <?php while ($p = $produtos_res->fetch_assoc()): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?> (R$
                                <?= number_format($p['preco'], 2, ',', '.') ?>, estoque <?= $p['estoque'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                    <label>Quantidade</label>
                    <input type="number" name="quantidade" min="1" required>

                    <label>Desconto (R$)</label>
                    <input type="number" step="0.01" min="0" name="desconto" value="0">

                    <label>Forma de pagamento</label>
                    <select name="pagamento" required>
                        <option value="">-- escolha --</option>
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Cartão">Cartão</option>
                        <option value="PIX">PIX</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                    <label>Cliente</label>
                    <select name="cliente" required>
                        <option value="">-- escolha --</option>
                        <?php while ($c = $clientes_res->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($c['nome']) ?>"><?= htmlspecialchars($c['nome']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label>Vendedor</label>
                    <select name="vendedor_id" required>
                        <option value="">-- escolha vendedor --</option>
                        <?php $vendedores_res->data_seek(0);
                        while ($v = $vendedores_res->fetch_assoc()): ?>
                            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nome']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div class="btn-area">
                        <button type="submit" class="btn-add">Finalizar Venda</button>
                    </div>
                </form>
                <div class="voltar"><a href="index.php">⬅ Voltar</a></div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>