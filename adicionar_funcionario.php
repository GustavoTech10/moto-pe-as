<?php
include 'conexao.php';

if(isset($_POST['nome'])){
    $nome = $conn->real_escape_string($_POST['nome']);
    $cargo = $conn->real_escape_string($_POST['cargo']);
    $salario = isset($_POST['salario']) ? floatval($_POST['salario']) : 0;
    $data_admissao = isset($_POST['data_admissao']) ? $_POST['data_admissao'] : '';

    if(empty($cargo)){
        $erro = "Cargo é obrigatório!";
    } else if(empty($data_admissao)){
        $erro = "Data de admissão é obrigatória!";
    } else {
        $stmt = $conn->prepare("INSERT INTO funcionarios (nome, cargo, salario, data_admissao) VALUES (?,?,?,?)");
        if($stmt){
            $stmt->bind_param("ssds", $nome, $cargo, $salario, $data_admissao);
            if($stmt->execute()){
                header("Location: funcionarios.php");
                exit;
            } else {
                $erro = "Erro ao cadastrar: ".$stmt->error;
            }
            $stmt->close();
        } else {
            $erro = "Erro na preparação: ".$conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Adicionar Funcionário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">GUSTAVO MOTOPEÇAS</div>
<div class="container">
    <div class="card-editar">
        <h1>Adicionar Funcionário</h1>
        <?php if(isset($erro)): ?>
            <div style="background:#220000;color:red;padding:10px;margin-bottom:10px;border-radius:6px;">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <label>Nome</label>
            <input type="text" name="nome" required>

            <label>Cargo</label>
            <select name="cargo" required>
                <option value="">-- escolha --</option>
                <option value="Vendedor">Vendedor</option>
                <option value="Mecânico">Mecânico</option>
                <option value="Gestor">Gestor</option>
                <option value="Financeiro">Financeiro</option>
            </select>

            <label>Salário (R$)</label>
            <input type="number" name="salario" step="0.01" required>

            <label>Data Admissão</label>
            <input type="date" name="data_admissao" required>

            <div class="btn-area">
                <button type="submit" class="btn-add">SALVAR FUNCIONÁRIO</button>
            </div>
        </form>
        <div class="voltar"><a href="funcionarios.php">⬅ Voltar</a></div>
    </div>
</div>
</body>
</html>