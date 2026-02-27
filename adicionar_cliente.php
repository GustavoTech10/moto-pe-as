<?php
include 'conexao.php';

if(isset($_POST['nome'])){

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    
    // PHP: Remove tudo que não for número e limita a 11 caracteres
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $cpf = substr($cpf, 0, 11); 
    
    $cidade = $_POST['cidade'];

    // Verifica se email ou cpf já existem
    $check = $conn->prepare("SELECT id FROM clientes WHERE email = ? OR cpf = ?");
    $check->bind_param("ss", $email, $cpf);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Erro: E-mail ou CPF já cadastrado!'); window.history.back();</script>";
        $check->close();
    } else {
        $check->close();

        $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone, email, cpf, cidade) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $telefone, $email, $cpf, $cidade);

        try {
            if($stmt->execute()){
                header("Location: clientes.php");
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            echo "Erro: " . $e->getMessage();
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Cliente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    GUSTAVO MOTOPEÇAS
</div>

<div class="container">
    <div class="card-editar">
        <h1>Adicionar Cliente</h1>
        <form method="POST">
            <label>Nome</label>
            <input type="text" name="nome" required>

            <label>Telefone</label>
            <input type="text" name="telefone" placeholder="(00) 00000-0000">

            <label>Email</label>
            <input type="email" name="email">

            <label>CPF</label>
            <!-- HTML: maxlength impede que digitem mais de 11 caracteres -->
            <!-- pattern garante que apenas números sejam aceitos -->
            <input type="text" name="cpf" maxlength="11" pattern="\d{11}" title="Digite exatamente 11 números" required>

            <label>Cidade</label>
            <input type="text" name="cidade" required>

            <div class="btn-area">
                <button type="submit" class="btn-add">SALVAR CLIENTE</button>
            </div>
        </form>
        <div class="voltar">
            <a href="clientes.php">⬅ Voltar</a>
        </div>
    </div>
</div>

</body>
</html>
