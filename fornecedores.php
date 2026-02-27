<?php
include 'conexao.php';

$sql = "SELECT * FROM fornecedores ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fornecedores</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .action-menu { position: relative; display: inline-block; }
        .action-dropdown { display: none; position: absolute; background: #111; border: 1px solid red; right: 0; min-width: 120px; z-index: 10; }
        .action-dropdown a { display: block; color: white; padding: 8px 10px; text-decoration: none; }
        .action-dropdown a:hover { background: #220000; }
        .acoes-btn { background: black; color: red; border: 1px solid red; padding: 6px 10px; border-radius:6px; cursor:pointer; }
    </style>
</head>
<body>

<div class="navbar">GUSTAVO MOTOPEÇAS</div>
<div class="container">
    <div class="table-header">
        <h2>FORNECEDORES</h2>
        <div>
            <a href="index.php"><button class="btn-add">Voltar</button></a>
            <a href="adicionar_fornecedor.php"><button class="btn-add">Adicionar Fornecedor</button></a>
        </div>
    </div>
    <table>
    <tr>
    <th>Nome</th>
    <th>CNPJ</th>
    <th>Telefone</th>
    <th>Ações</th>
    </tr>

    <?php if($result && $result->num_rows>0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
    <td><?= htmlspecialchars($row['nome']) ?></td>
    <td><?= htmlspecialchars($row['cnpj']) ?></td>
    <td><?= htmlspecialchars($row['telefone']) ?></td>
    <td>
        <div class="action-menu">
            <button class="acoes-btn" onclick="toggleMenu('f<?= $row['id'] ?>')">Ações ▾</button>
            <div id="f<?= $row['id'] ?>" class="action-dropdown">
                <a href="editar_fornecedor.php?id=<?= $row['id'] ?>">Editar</a>
                <a href="excluir_fornecedor.php?id=<?= $row['id'] ?>" onclick="return confirm('Excluir fornecedor?')">Excluir</a>
            </div>
        </div>
    </td>
    </tr>
    <?php endwhile; ?>
    <?php else: ?>
    <tr><td colspan="4">Nenhum fornecedor cadastrado</td></tr>
    <?php endif; ?>
    </table>
</div>

<script>
function toggleMenu(id){
    document.querySelectorAll('.action-dropdown').forEach(function(el){ el.style.display='none'; });
    var el = document.getElementById(id);
    if(el) el.style.display = (el.style.display === 'block')? 'none' : 'block';
}
window.addEventListener('click', function(e){
    if(!e.target.matches('.acoes-btn')){
        document.querySelectorAll('.action-dropdown').forEach(function(el){ el.style.display='none'; });
    }
});
</script>

</body>
</html>