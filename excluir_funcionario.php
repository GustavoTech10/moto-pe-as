<?php
include 'conexao.php';

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $sql = "DELETE FROM funcionarios WHERE id = $id";
    if($conn->query($sql)===TRUE){
        header("Location: funcionarios.php");
        exit;
    } else {
        echo "Erro ao excluir: ".$conn->error;
    }
} else {
    echo "ID não informado.";
}
?>