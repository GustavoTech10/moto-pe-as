<?php
include 'conexao.php';

// ================= PRODUTOS =================
$sql_produtos = "SELECT * FROM produtos ORDER BY id DESC";
$result_produtos = $conn->query($sql_produtos);

// ================= CLIENTES =================
$sql_clientes = "SELECT * FROM clientes";
$result_clientes = $conn->query($sql_clientes);

// ================= FORNECEDORES =================
$sql_fornecedores = "SELECT * FROM fornecedores";
$result_fornecedores = $conn->query($sql_fornecedores);

// ================= CADASTRAR PRODUTO =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto'])) {

    $nome = $conn->real_escape_string($_POST['produto']);
    $marca = $conn->real_escape_string($_POST['marca']);
    $preco = $conn->real_escape_string($_POST['preco']);
    $estoque = $conn->real_escape_string($_POST['estoque']);

    if (!empty($nome) && !empty($marca)) {

        $sql_insert = "INSERT INTO produtos (nome, marca, preco, estoque) 
                       VALUES ('$nome', '$marca', '$preco', '$estoque')";

        if ($conn->query($sql_insert)) {
            header("Location: index.php");
            exit;
        } else {
            echo "Erro ao cadastrar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<!-- BOTÃO VENDAS NO CANTO DIREITO -->
<div class="vendas-topo">
    <a href="vendas.php">
        <button class="btn-dashboard" title="Ver Vendas">
            <i class="fas fa-shopping-cart"></i> VENDAS
        </button>
    </a>
    <a href="adicionar_venda.php" style="margin-left:8px;">
        <button class="btn-dashboard" title="Registrar Venda">
            <i class="fas fa-plus"></i> NOVA VENDA
        </button>
    </a>
</div>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gustavo Moto Peças</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
        }

        /* BODY */
        body {
            font-family: Arial;
            background-color: #0b0b0b;
            color: white;
        }

        /* NAVBAR */
        .navbar {
            background: black;
            padding: 15px;
            font-size: 22px;
            color: red;
            border-bottom: 3px solid darkred;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;

        }

        /* BOTÃO DASHBOARD */
        .dashboard-topo {
            position: absolute;
            left: 15px;
            z-index: 9999;
        }

        /* BOTÃO VENDAS (topo direito) */
        .vendas-topo {
            position: absolute;
            right: 15px;
            z-index: 9999;
        }

        .btn-dashboard {
            background-color: #8B0000;
            color: white;
            border: none;
            padding: 6px 14px;
            font-size: 13px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-dashboard:hover {
            background-color: red;
        }

        /* CONTAINER */
        .container {
            padding: 20px;
        }

        /* TITULOS */
        h2 {
            color: red;
            margin-bottom: 10px;
        }

        /* TABELA */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #111;
            margin-bottom: 30px;
        }

        th {
            background: black;
            color: red;
            padding: 10px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #333;
            text-align: center;
        }

        tr:hover {
            background: #220000;
        }

        /* BOTÕES */
        button {
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            font-weight: bold;
        }

        .btn-add {
            background: darkred;
            color: white;
            padding: 8px 15px;
        }

        .btn-add:hover {
            background: red;
        }

        /* action dropdown for tables */
        .action-menu {
            position: relative;
            display: inline-block;
        }

        .action-dropdown {
            display: none;
            position: absolute;
            background: #111;
            border: 1px solid red;
            right: 0;
            min-width: 120px;
            z-index: 10;
        }

        .action-dropdown a {
            display: block;
            color: white;
            padding: 8px 10px;
            text-decoration: none;
        }

        .action-dropdown a:hover {
            background: #220000;
        }

        .acoes-btn {
            background: black;
            color: red;
            border: 1px solid red;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        /* HEADER TABELA */
        .table-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        /* FORM CARD */
        .form-container {
            display: none;
            width: 420px;
            margin: 20px auto;
            background: #111;
            padding: 25px;
            border-radius: 12px;
            border-top: 4px solid red;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
        }

        /* LABEL */
        label {
            display: block;
            margin-top: 10px;
        }

        /* INPUT */
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            background: black;
            color: white;
            border: 1px solid red;
            border-radius: 6px;
        }

        /* BOTÃO SALVAR */
        .btn-salvar {
            width: 100%;
            margin-top: 15px;
            padding: 10px;
            background: darkred;
            color: white;
        }

        .btn-salvar:hover {
            background: red;
        }
    </style>
</head>

<body>
    <div class="navbar">

        <!-- BOTÃO DASHBOARD -->
        <div class="dashboard-topo">
            <a href="dashboard.php">
                <button class="btn-dashboard">
                    <i class="fas fa-chart-line"></i> DASHBOARD
                </button>
            </a>
            <a href="painel_adm.php" style="margin-left:8px;">
                <button class="btn-dashboard" style="background-color: #ff9500; color: black;">
                    <i class="fas fa-lock"></i> PAINEL ADM
                </button>
            </a>
        </div>

        <!-- NAVBAR -->
        GUSTAVO MOTO PEÇAS
        <i class="fas fa-tools"></i>
    </div>

    <div class="container">

        <!-- PRODUTOS -->
        <div class="table-header">
            <h2>PRODUTOS</h2>
            <button class="btn-add" onclick="toggleForm()">Cadastrar Produto</button>
        </div>

        <!-- FORM -->
        <div id="formProduto" class="form-container">

            <form method="POST">

                <label>Produto</label>
                <input type="text" name="produto" required>

                <label>Marca</label>
                <input type="text" name="marca" required>

                <label>Preço</label>
                <input type="number" step="0.01" name="preco" required>

                <label>Estoque</label>
                <input type="number" name="estoque" required>

                <button class="btn-salvar" type="submit">
                    Salvar Produto
                </button>

            </form>
        </div>

        <!-- TABELA PRODUTOS -->
        <table>
            <tr>
                <th>Nome</th>
                <th>Marca</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>

            <?php if ($result_produtos->num_rows > 0): ?>
                <?php while ($row = $result_produtos->fetch_assoc()): ?>
                    <tr>

                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars($row['marca']) ?></td>
                        <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
                        <td><?= $row['estoque'] ?></td>

                        <td>
                            <div class="action-menu">
                                <button class="acoes-btn" onclick="toggleMenu('p<?= $row['id'] ?>')">Ações ▾</button>
                                <div id="p<?= $row['id'] ?>" class="action-dropdown">
                                    <a href="editar_produto.php?id=<?= $row['id'] ?>">Editar</a>
                                    <a href="excluir_produto.php?id=<?= $row['id'] ?>"
                                        onclick="return confirm('Excluir produto?')">Excluir</a>
                                </div>
                            </div>
                        </td>

                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum produto cadastrado</td>
                </tr>
            <?php endif; ?>

        </table>

        <!-- CLIENTES -->
        <div class="table-header">
            <h2>CLIENTES</h2>
            <a href="adicionar_cliente.php">
                <button class="btn-add">Adicionar Cliente</button>
            </a>
        </div>

        <table>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>

            <?php while ($row = $result_clientes->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['telefone']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <div class="action-menu">
                            <button class="acoes-btn" onclick="toggleMenu('c<?= $row['id'] ?>')">Ações ▾</button>
                            <div id="c<?= $row['id'] ?>" class="action-dropdown">
                                <a href="editar_cliente.php?id=<?= $row['id'] ?>">Editar</a>
                                <a href="excluir_cliente.php?id=<?= $row['id'] ?>"
                                    onclick="return confirm('Excluir cliente?')">Excluir</a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>

        <!-- FORNECEDORES -->
        <div class="table-header">
            <h2>FORNECEDORES</h2>
            <a href="adicionar_fornecedor.php"><button class="btn-add">Adicionar Fornecedor</button></a>
        </div>

        <table>
            <tr>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>

            <?php while ($row = $result_fornecedores->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['cnpj']) ?></td>
                    <td><?= htmlspecialchars($row['telefone']) ?></td>
                    <td>
                        <div class="action-menu">
                            <button class="acoes-btn" onclick="toggleMenu('f<?= $row['id'] ?>')">Ações ▾</button>
                            <div id="f<?= $row['id'] ?>" class="action-dropdown">
                                <a href="editar_fornecedor.php?id=<?= $row['id'] ?>">Editar</a>
                                <a href="excluir_fornecedor.php?id=<?= $row['id'] ?>"
                                    onclick="return confirm('Excluir fornecedor?')">Excluir</a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>

        <div style="text-align:right; margin-top:10px;">
            <a href="funcionarios.php"><button class="btn-add">Funcionários</button></a>
            <a href="adicionar_funcionario.php"><button class="btn-dashboard" style="margin-left:8px;">Novo
                    Funcionário</button></a>
        </div>

    </div>

    <script>
        function toggleForm() {
            var form = document.getElementById("formProduto");

            if (form.style.display === "block") {
                form.style.display = "none";
            } else {
                form.style.display = "block";
            }
        }

        function toggleMenu(id) {
            document.querySelectorAll('.action-dropdown').forEach(function (el) { el.style.display = 'none'; });
            var el = document.getElementById(id);
            if (el) el.style.display = (el.style.display === 'block') ? 'none' : 'block';
        }

        window.addEventListener('click', function (e) {
            if (!e.target.matches('.acoes-btn')) {
                document.querySelectorAll('.action-dropdown').forEach(function (el) { el.style.display = 'none'; });
            }
        });
    </script>

</body>

</html>