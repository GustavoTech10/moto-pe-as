<?php
include 'conexao.php';

if(isset($_POST['id'])){
    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string($_POST['nome']);
    $cnpj = preg_replace('/[^0-9]/','',$_POST['cnpj']);
    $cnpj = substr($cnpj,0,14);
    $telefone = $conn->real_escape_string($_POST['telefone']);

    $sql = "UPDATE fornecedores SET 
            nome='$nome',
            cnpj='$cnpj',
            telefone='$telefone'
            WHERE id=$id";
    if($conn->query($sql)===TRUE){
        header("Location: fornecedores.php");
        exit;
    } else {
        echo "Erro: " . $conn->error;
    }
}

if(!isset($_GET['id'])){ echo 'ID não informado.'; exit; }
$id = intval($_GET['id']);
$sql = "SELECT * FROM fornecedores WHERE id=$id";
$result = $conn->query($sql);
if(!$result || $result->num_rows==0){ echo 'Fornecedor não encontrado.'; exit; }
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Editar Fornecedor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">GUSTAVO MOTOPEÇAS</div>

<div class="container">
<div class="card">
<h1>Editar Fornecedor</h1>

<form method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">

<label>Nome</label>
<input type="text" name="nome" value="<?= htmlspecialchars($row['nome']) ?>" required>

<label>CNPJ</label>
<input type="text" name="cnpj" maxlength="14" pattern="\d{14}" value="<?= htmlspecialchars($row['cnpj']) ?>" required>

<label>Telefone</label>
<input type="text" name="telefone" value="<?= htmlspecialchars($row['telefone']) ?>">

<input type="submit" class="btn-salvar" value="Salvar">
</form>

<div class="voltar"><a href="fornecedores.php">Voltar</a></div>
</div>
</div>

</body>
</html>