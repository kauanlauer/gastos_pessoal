<?php
include('../Login/protect.php');
include('../Login/conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $mysqli->real_escape_string($_POST['id']);

    $sql = "DELETE FROM despesas WHERE id='$id'";
    if ($mysqli->query($sql)) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('error' => 'Erro ao excluir despesa.'));
    }
}
?>
