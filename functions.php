<?php
function getTotalDespesasMensais($userId, $mysqli) {
    $currentMonth = date('Y-m-01'); // Primeiro dia do mês atual
    $sql = "SELECT SUM(valor) as total FROM despesas WHERE user_id = '$userId' AND data >= '$currentMonth'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getDespesasMensais($userId, $mysqli) {
    $currentMonth = date('Y-m-01'); // Primeiro dia do mês atual
    $sql = "SELECT descricao, valor, data FROM despesas WHERE user_id = '$userId' AND data >= '$currentMonth' ORDER BY data DESC";
    return $mysqli->query($sql);
}
?>
