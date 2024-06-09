<?php
include('../Login/protect.php');
include('../Login/conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $mysqli->real_escape_string($_POST['id']);
    $descricao = $mysqli->real_escape_string($_POST['descricao']);
    $valor = $mysqli->real_escape_string($_POST['valor']);
    $data = $mysqli->real_escape_string($_POST['data']);
    $informacao = $mysqli->real_escape_string($_POST['informacao']);

    $sql = "UPDATE despesas SET descricao='$descricao', valor='$valor', data='$data', informacao='$informacao' WHERE id='$id'";
    if ($mysqli->query($sql)) {
        $response = array(
            'id' => $id,
            'descricao' => $descricao,
            'valor' => $valor,
            'data' => date('d/m/Y', strtotime($data)),
            'informacao' => $informacao
        );
        echo json_encode($response);
    } else {
        echo json_encode(array('error' => 'Erro ao atualizar despesa.'));
    }
}
?>
