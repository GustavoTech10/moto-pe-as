<?php
include 'conexao.php';

if(isset($_POST['nome'])){
    $nome = $_POST['nome'];
    $cnpj = preg_replace('/[^0-9]/','',$_POST['cnpj']);
    $cnpj = substr($cnpj,0,14);
    $telefone = $_POST['telefone'];

    // checar cnpj duplicado
    $check = $conn->prepare("SELECT id FROM fornecedores WHERE cnpj = ?");
    $check->bind_param("s", $cnpj);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        echo "<script>alert('Erro: CNPJ já cadastrado!'); window.history.back();</script>";
        $check->close();
    } else {
        $check->close();
        $stmt = $conn->prepare("INSERT INTO fornecedores (nome, cnpj, telefone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $cnpj, $telefone);
        if($stmt->execute()){
            header("Location: fornecedores.php");
            exit;
        } else {
            echo "Erro: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Fornecedor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">GUSTAVO MOTOPEÇAS</div>

<div class="container">
    <div class="card-editar">
        <h1>Adicionar Fornecedor</h1>
        <form method="POST">
            <label>Nome</label>
            <input type="text" name="nome" required>
            <label>CNPJ</label>
            <input type="text" name="cnpj" maxlength="14" pattern="\d{14}" title="Digite 14 números" required>
            <label>Telefone</label>
            <input type="text" name="telefone" placeholder="(00) 00000-0000">
            <div class="btn-area">
                <button type="submit" class="btn-add">SALVAR FORNECEDOR</button>
            </div>
        </form>
        <div class="voltar"><a href="fornecedores.php">⬅ Voltar</a></div>
    </div>
</div>

</body>
</html>