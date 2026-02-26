<?php
include("conexao.php");

if(isset($_POST['nome'])){

$nome = $_POST['nome'];
$marca = $_POST['marca'];
$valor = $_POST['valor'];

mysqli_query($conn,
"INSERT INTO produtos (nome,marca,valor)
VALUES ('$nome','$marca','$valor')");

header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cadastrar</title>

<style>

body {
    font-family: Arial;
    background: #0b0b0b;
    color: white;
}

.form-box {
    background: #111;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0px 0px 20px red;
    max-width: 500px;
    margin: 80px auto;
}

h2 {
    text-align: center;
    color: red;
}

input {
    width: 100%;
    padding: 10px;
    margin-bottom: 12px;
    border-radius: 6px;
    border: none;
}

button {
    width: 100%;
    padding: 12px;
    background: red;
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 8px;
}

</style>
</head>

<body>

<div class="form-box">

<h2>Cadastrar Produto</h2>

<form method="POST">

<input name="nome" placeholder="Nome">
<input name="marca" placeholder="Marca">
<input name="valor" placeholder="Valor">

<button>Salvar</button>

</form>

</div>

</body>
</html>