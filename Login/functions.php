<?php
function getTotalDespesasMensais($userId, $mysqli) {
    
    $currentMonth = date('Y-m'); // Obtém o ano e o mês atuais no formato 'YYYY-MM'
    $sql = "SELECT SUM(valor) as total FROM despesas WHERE user_id = '$userId' AND DATE_FORMAT(data, '%Y-%m') = '$currentMonth'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    
    return $row['total'];
    
    
}

function getDespesasMensais($userId, $mysqli, $limit = 5) {
    $currentMonth = date('Y-m'); // Obtém o ano e o mês atuais no formato 'YYYY-MM'
    $sql = "SELECT descricao, valor, data FROM despesas WHERE user_id = '$userId' AND DATE_FORMAT(data, '%Y-%m') = '$currentMonth' ORDER BY data DESC LIMIT $limit";
    return $mysqli->query($sql);
}

function getDespesas($userId, $mysqli, $dataInicial, $dataFinal) {
    $sql = "SELECT id, descricao, valor, data, informacao FROM despesas WHERE user_id = '$userId' ";
    if (!empty($dataInicial) && !empty($dataFinal)) {
        $sql .= "AND data BETWEEN '$dataInicial' AND '$dataFinal' ";
    }
    $sql .= "ORDER BY data DESC";
    return $mysqli->query($sql);
}

$dataInicial = $_GET['dataInicial'] ?? '';
$dataFinal = $_GET['dataFinal'] ?? '';
$despesas = getDespesas($_SESSION['id'], $mysqli, $dataInicial, $dataFinal);

?>
