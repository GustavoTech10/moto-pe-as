<?php
include 'conexao.php';

// SALVAR EDIÇÃO
if(isset($_POST['id'])) {

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $marca = $_POST['marca'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];

    $sql = "UPDATE produtos SET 
            nome='$nome',
            marca='$marca',
            preco='$preco',
            estoque='$estoque'
            WHERE id=$id";

    if($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro: " . $conn->error;
    }
}

// BUSCAR PRODUTO
$id = $_GET['id'];
$sql = "SELECT * FROM produtos WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Produto</title>

<style>

/* FUNDO */
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #000000, #1a1a1a);
    color: white;
}

/* CARD CENTRAL */
.card {
    width: 400px;
    margin: 80px auto;
    padding: 30px;
    border-radius: 15px;
    background: #111;
    box-shadow: 0 0 20px red;
}

/* TITULO */
.card h1 {
    text-align: center;
    color: red;
    margin-bottom: 20px;
}

/* LABEL */
label {
    display: block;
    margin-top: 10px;
    margin-bottom: 5px;
}

/* INPUT */
input[type=text],
input[type=number] {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid red;
    background: black;
    color: white;
}

/* BOTÃO */
input[type=submit] {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    background: red;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    cursor: pointer;
}

input[type=submit]:hover {
    background: darkred;
    box-shadow: 0 0 10px red;
}

/* VOLTAR */
.voltar {
    text-align: center;
    margin-top: 15px;
}

.voltar a {
    color: red;
    text-decoration: none;
}

</style>

</head>
<body>

<div class="card">

<h1>Editar Produto</h1>

<form method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<label>Nome:</label>
<input type="text" name="nome" value="<?php echo $row['nome']; ?>">

<label>Marca:</label>
<input type="text" name="marca" value="<?php echo $row['marca']; ?>">

<label>Preço:</label>
<input type="text" name="preco" value="<?php echo $row['preco']; ?>">

<label>Estoque:</label>
<input type="number" name="estoque" value="<?php echo $row['estoque']; ?>">

<input type="submit" value="Salvar">

</form>

<div class="voltar">
<a href="index.php">Voltar</a>
</div>

</div>

</body>
</html>