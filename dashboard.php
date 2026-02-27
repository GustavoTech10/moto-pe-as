<?php
include 'conexao.php';

// CONTAGENS
$total_produtos = $conn->query("SELECT COUNT(*) as total FROM produtos")->fetch_assoc()['total'];
$total_clientes = $conn->query("SELECT COUNT(*) as total FROM clientes")->fetch_assoc()['total'];
$total_fornecedores = $conn->query("SELECT COUNT(*) as total FROM fornecedores")->fetch_assoc()['total'];
// VENDAS: total de peças vendidas e receita
$total_vendas = $conn->query("SELECT IFNULL(SUM(quantidade),0) as total FROM vendas")->fetch_assoc()['total'];
$total_receita = $conn->query("SELECT IFNULL(SUM(total),0) as soma FROM vendas")->fetch_assoc()['soma'];

// PRODUTOS
$sql_produtos = "SELECT * FROM produtos";
$result_produtos = $conn->query($sql_produtos);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Motopeças</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    GUSTAVO MOTOPEÇAS - DASHBOARD
</div>

<div class="container">

<a href="index.php">
        <button class="btn-voltar">Voltar para Início</button>
    </a>

    <div class="cards">
    <div class="card">
        <h2 class="piscar">PRODUTOS</h2>
        <p><?php echo $total_produtos; ?></p>
    </div>

    <div class="card">
        <h2 class="piscar">CLIENTES</h2>
        <p><?php echo $total_clientes; ?></p>
    </div>

    <div class="card">
        <h2 class="piscar">FORNECEDORES</h2>
        <p><?php echo $total_fornecedores; ?></p>
    </div>
    
    <div class="card">
        <h2 class="piscar">PEÇAS VENDIDAS</h2>
        <p><?php echo $total_vendas; ?></p>
        <small style="display:block;margin-top:6px;color:#ddd;">R$ <?php echo number_format($total_receita,2,',','.'); ?></small>
    </div>
</div>

    <h1>Produtos</h1>

    <br><br>

    <table>
        <tr>
            <th>Nome</th>
            <th>Marca</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Ações</th>
        </tr>

        <?php
        while($row = $result_produtos->fetch_assoc()) {
            echo "<tr>
                    <td>".$row['nome']."</td>
                    <td>".$row['marca']."</td>
                    <td>R$ ".$row['preco']."</td>
                    <td>".$row['estoque']."</td>
                    <td>
                        <a href='editar_produto.php?id=".$row['id']."'>
                            <button class='btn-edit'>Editar</button>
                        </a>

                        <a href='excluir_produto.php?id=".$row['id']."'
                           onclick=\"return confirm('Excluir produto?')\">
                            <button class='btn-delete'>Excluir</button>
                        </a>
                    </td>
                  </tr>";
        }
        ?>

    </table>

</div>

</body>
</html>