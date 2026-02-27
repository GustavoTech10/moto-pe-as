<?php
include 'conexao.php';

// SALVAR EDIÇÃO
if(isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string($_POST['nome']);
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $email = $conn->real_escape_string($_POST['email']);
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $cpf = substr($cpf, 0, 11);
    $cidade = $conn->real_escape_string($_POST['cidade']);

    $sql = "UPDATE clientes SET 
            nome='$nome',
            telefone='$telefone',
            email='$email',
            cpf='$cpf',
            cidade='$cidade'
            WHERE id=$id";

    if($conn->query($sql) === TRUE) {
        header("Location: clientes.php");
        exit();
    } else {
        echo "Erro: " . $conn->error;
    }
}

// BUSCAR CLIENTE
if(!isset($_GET['id'])){ echo 'ID não informado.'; exit; }
$id = intval($_GET['id']);
$sql = "SELECT * FROM clientes WHERE id = $id";
$result = $conn->query($sql);
if(!$result || $result->num_rows == 0){ echo 'Cliente não encontrado.'; exit; }
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">GUSTAVO MOTOPEÇAS</div>

<div class="container">
<div class="card">
<h1>Editar Cliente</h1>

<form method="POST">
<input type="hidden" name="id" value="<?= $row['id'] ?>">

<label>Nome</label>
<input type="text" name="nome" value="<?= htmlspecialchars($row['nome']) ?>" required>

<label>Telefone</label>
<input type="text" name="telefone" value="<?= htmlspecialchars($row['telefone']) ?>">

<label>Email</label>
<input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>">

<label>CPF</label>
<input type="text" name="cpf" maxlength="11" pattern="\d{11}" value="<?= htmlspecialchars($row['cpf']) ?>" required>

<label>Cidade</label>
<input type="text" name="cidade" value="<?= htmlspecialchars($row['cidade']) ?>" required>

<input type="submit" class="btn-salvar" value="Salvar">
</form>

<div class="voltar"><a href="clientes.php">Voltar</a></div>
</div>
</div>

</body>
</html>