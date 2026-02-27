<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "loja_moto_pecas";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// garantir tabelas para vendas e caixa
$sql_vendas = "CREATE TABLE IF NOT EXISTS vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    desconto DECIMAL(10,2) DEFAULT 0,
    pagamento VARCHAR(50),
    vendedor VARCHAR(255),
    cliente VARCHAR(255),
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB";
$conn->query($sql_vendas);

// caso alguma instalação anterior tenha criado a tabela sem a coluna quantidade
$check = $conn->query("SHOW COLUMNS FROM vendas LIKE 'quantidade'");
if($check && $check->num_rows === 0) {
    $conn->query("ALTER TABLE vendas ADD COLUMN quantidade INT NOT NULL DEFAULT 0");
}
// garantir colunas desconto e pagamento se estiverem ausentes
$check = $conn->query("SHOW COLUMNS FROM vendas LIKE 'desconto'");
if($check && $check->num_rows === 0) {
    $conn->query("ALTER TABLE vendas ADD COLUMN desconto DECIMAL(10,2) DEFAULT 0");
}
$check = $conn->query("SHOW COLUMNS FROM vendas LIKE 'pagamento'");
if($check && $check->num_rows === 0) {
    $conn->query("ALTER TABLE vendas ADD COLUMN pagamento VARCHAR(50)");
}

$sql_caixa = "CREATE TABLE IF NOT EXISTS caixa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255),
    valor DECIMAL(10,2),
    forma_pagamento VARCHAR(50),
    vendedor VARCHAR(255),
    cliente VARCHAR(255),
    data DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";
$conn->query($sql_caixa);
// garantir coluna forma_pagamento em caixa
$check = $conn->query("SHOW COLUMNS FROM caixa LIKE 'forma_pagamento'");
if($check && $check->num_rows === 0) {
    $conn->query("ALTER TABLE caixa ADD COLUMN forma_pagamento VARCHAR(50)");
}

// garantir tabela funcionarios
$sql_func = "CREATE TABLE IF NOT EXISTS funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    cargo VARCHAR(100),
    salario DECIMAL(10,2),
    data_admissao DATE
) ENGINE=InnoDB";
$conn->query($sql_func);
?>
