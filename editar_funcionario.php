<?php
include 'conexao.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string($_POST['nome']);
    $cargo = $conn->real_escape_string($_POST['cargo']);
    // tratamento de salário: permitir valores fracionários (decimais)
    $salario = floatval(str_replace(',', '.', $_POST['salario']));
    $data_admissao = $_POST['data_admissao'];

    $sql = "UPDATE funcionarios SET nome='$nome', cargo='$cargo', salario=$salario, data_admissao='$data_admissao' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: funcionarios.php");
        exit;
    } else {
        echo "Erro: " . $conn->error;
    }
}

if (!isset($_GET['id'])) {
    echo 'ID não informado.';
    exit;
}
$id = intval($_GET['id']);
$sql = "SELECT * FROM funcionarios WHERE id=$id";
$result = $conn->query($sql);
if (!$result || $result->num_rows == 0) {
    echo 'Funcionário não encontrado.';
    exit;
}
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="navbar">GUSTAVO MOTOPEÇAS</div>

    <div class="container">
        <div class="card-editar">
            <h1>Editar Funcionário</h1>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">

                <label>Nome</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($row['nome']) ?>" required>

                <label>Cargo</label>
                <select name="cargo" required>
                    <option value="">-- escolha --</option>
                    <option value="Vendedor" <?= $row['cargo'] === 'Vendedor' ? 'selected' : '' ?>>Vendedor</option>
                    <option value="Mecânico" <?= $row['cargo'] === 'Mecânico' ? 'selected' : '' ?>>Mecânico</option>
                    <option value="Gestor" <?= $row['cargo'] === 'Gestor' ? 'selected' : '' ?>>Gestor</option>
                    <option value="Financeiro" <?= $row['cargo'] === 'Financeiro' ? 'selected' : '' ?>>Financeiro</option>
                </select>

                <label>Salário (R$)</label>
                <!-- máscara de moeda brasileira em tempo real -->
                <input type="text" id="salario-display" placeholder="R$ 0,00" required>
                <input type="hidden" name="salario" id="salario-value" value="<?= floatval($row['salario']) ?>">

                <script>
                    // Função para formatar como moeda BR
                    function formatarMoeda(valor) {
                        valor = valor.replace(/\D/g, '');
                        valor = (valor / 100).toFixed(2);
                        return 'R$ ' + valor.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }

                    // Função para extrair número puro
                    function extrairNumero(valor) {
                        return parseFloat(valor.replace(/\D/g, '')) / 100;
                    }

                    const inputDisplay = document.getElementById('salario-display');
                    const inputValue = document.getElementById('salario-value');

                    // Carregar valor inicial formatado
                    const valorInicial = parseFloat(inputValue.value);
                    if (valorInicial > 0) {
                        inputDisplay.value = formatarMoeda((valorInicial * 100).toString());
                    }

                    // Formatar enquanto digita
                    inputDisplay.addEventListener('input', function () {
                        this.value = formatarMoeda(this.value);
                    });

                    // Atualizar campo hidden com valor numérico antes de enviar
                    document.querySelector('form').addEventListener('submit', function (e) {
                        inputValue.value = extrairNumero(inputDisplay.value);
                    });
                </script>

                <label>Data Admissão</label>
                <input type="date" name="data_admissao" value="<?= htmlspecialchars($row['data_admissao']) ?>" required>

                <input type="submit" class="btn-salvar" value="Salvar">
            </form>

            <div class="voltar"><a href="funcionarios.php">Voltar</a></div>
        </div>
    </div>

</body>

</html>