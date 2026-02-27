<?php
include 'conexao.php';

if(!$conn){
    die('Erro de conexão');
}

$sql = "SELECT * FROM funcionarios ORDER BY id DESC";
$result = $conn->query($sql);

if(!$result){
    die('Erro na query: '.$conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Funcionários</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .action-menu { position: relative; display: inline-block; }
        .action-dropdown { display: none; position: absolute; background: #111; border: 1px solid red; right: 0; min-width: 120px; z-index: 1000; top: 100%; }
        .action-dropdown a { display: block; color: white; padding: 8px 10px; text-decoration: none; font-size: 13px; }
        .action-dropdown a:hover { background: #220000; }
        .acoes-btn { background: black; color: red; border: 1px solid red; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-size: 12px; }
        .badge-admin { background: #ff9500; color: black; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; margin-left: 5px; }
    </style>
</head>
<body>
<div class="navbar">GUSTAVO MOTOPEÇAS</div>
<div class="container">
    <div class="table-header">
        <h2>FUNCIONÁRIOS</h2>
        <div>
            <a href="index.php"><button class="btn-voltar">Voltar</button></a>
            <a href="adicionar_funcionario.php"><button class="btn-add">Adicionar Funcionário</button></a>
        </div>
    </div>

    <table>
        <tr>
            <th>Nome</th>
            <th>Cargo</th>
            <th>Salário</th>
            <th>Data Admissão</th>
            <th>Ações</th>
        </tr>
        <?php
        if($result && $result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $id = intval($row['id']);
                $nome = isset($row['nome']) ? htmlspecialchars($row['nome']) : '';
                $cargo = isset($row['cargo']) ? htmlspecialchars($row['cargo']) : '';
                $cargo_display = $cargo . ($cargo === 'Gestor' ? '<span class="badge-admin">ADM</span>' : '');
                $salario = isset($row['salario']) && $row['salario'] !== null ? 'R$ ' . number_format($row['salario'], 2, ',', '.') : '';
                $data_admissao = isset($row['data_admissao']) && $row['data_admissao'] !== null ? date('d/m/Y', strtotime($row['data_admissao'])) : '';
                
                echo '<tr>';
                echo '<td>' . $nome . '</td>';
                echo '<td>' . $cargo_display . '</td>';
                echo '<td>' . $salario . '</td>';
                echo '<td>' . $data_admissao . '</td>';
                echo '<td>';
                echo '<div class="action-menu">';
                echo '<button class="acoes-btn" onclick="abrirMenu(' . $id . ')">Ações ▾</button>';
                echo '<div id="menu' . $id . '" class="action-dropdown">';
                echo '<a href="editar_funcionario.php?id=' . $id . '">Editar</a>';
                echo '<a href="excluir_funcionario.php?id=' . $id . '" onclick="return confirm(\'Excluir funcionário?\')">Excluir</a>';
                echo '</div>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="5">Nenhum funcionário cadastrado</td></tr>';
        }
        ?>
    </table>
</div>

<script>
function abrirMenu(id) {
    var menuId = 'menu' + id;
    var menu = document.getElementById(menuId);
    if (!menu) return;
    
    var dropdowns = document.getElementsByClassName('action-dropdown');
    for (var i = 0; i < dropdowns.length; i++) {
        if (dropdowns[i].id !== menuId) {
            dropdowns[i].style.display = 'none';
        }
    }
    
    menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
}

document.onclick = function(e) {
    if (!e.target.matches('.acoes-btn')) {
        var dropdowns = document.getElementsByClassName('action-dropdown');
        for (var i = 0; i < dropdowns.length; i++) {
            dropdowns[i].style.display = 'none';
        }
    }
};
</script>
</body>
</html>