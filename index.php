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

.btn-edit {
    background: red;
    color: white;
}

.btn-delete {
    background: black;
    color: red;
    border: 1px solid red;
}

.btn-add {
    background: darkred;
    color: white;
    padding: 8px 15px;
}

.btn-add:hover {
    background: red;
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
    box-shadow: 0 0 15px rgba(255,0,0,0.4);
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
<?php while($row = $result_produtos->fetch_assoc()): ?>
<tr>

<td><?= htmlspecialchars($row['nome']) ?></td>
<td><?= htmlspecialchars($row['marca']) ?></td>
<td>R$ <?= number_format($row['preco'],2,',','.') ?></td>
<td><?= $row['estoque'] ?></td>

<td>
<a href="editar_produto.php?id=<?= $row['id'] ?>">
<button class="btn-edit">Editar</button>
</a>

<a href="excluir_produto.php?id=<?= $row['id'] ?>"
onclick="return confirm('Excluir produto?')">
<button class="btn-delete">Excluir</button>
</a>
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
<a href="cadastrar_cliente.php">
<button class="btn-add">Cadastrar Cliente</button>
</a>
</div>

<table>
<tr>
<th>Nome</th>
<th>Telefone</th>
<th>Email</th>
</tr>

<?php while($row = $result_clientes->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['nome']) ?></td>
<td><?= $row['telefone'] ?></td>
<td><?= $row['email'] ?></td>
</tr>
<?php endwhile; ?>

</table>

<!-- FORNECEDORES -->
<h2>FORNECEDORES</h2>

<table>
<tr>
<th>Nome</th>
<th>CNPJ</th>
<th>Telefone</th>
</tr>

<?php while($row = $result_fornecedores->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['nome']) ?></td>
<td><?= $row['cnpj'] ?></td>
<td><?= $row['telefone'] ?></td>
</tr>
<?php endwhile; ?>

</table>

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
</script>

</body>
</html>