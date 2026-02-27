<?php
include 'conexao.php';

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $sql = "DELETE FROM fornecedores WHERE id = $id";
    if($conn->query($sql)===TRUE){
        header("Location: fornecedores.php");
        exit;
    } else {
        echo "Erro ao excluir: " . $conn->error;
    }
} else {
    echo "ID não informado.";
}
?>