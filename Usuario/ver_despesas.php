<?php
// Ativar exibição de erros antes de qualquer outra coisa
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../Login/protect.php');
include('../Login/conexao.php');

// Verificar se o perfil do usuário está completo
$user_id = $_SESSION['id'];
$sql_foto = "SELECT foto FROM usuarios WHERE id='$user_id'";
$result_foto = $mysqli->query($sql_foto);
$usuario_foto = $result_foto->fetch_assoc();
$foto = $usuario_foto['foto'] ?? '';
$sql_check_profile = "SELECT perfil_completo FROM usuarios WHERE id = '$user_id'";
$result_check_profile = $mysqli->query($sql_check_profile);

$perfil_completo = false; // Definir como falso por padrão

if ($result_check_profile && $result_check_profile->num_rows > 0) {
    $row = $result_check_profile->fetch_assoc();
    $perfil_completo = $row['perfil_completo'];
}

$sql = "SELECT nome FROM usuarios WHERE id='$user_id'";
$result = $mysqli->query($sql);
$usuario = $result->fetch_assoc();
$primeiro_nome = explode(' ', $usuario['nome'])[0];

function getDespesas($userId, $mysqli, $dataInicial = null, $dataFinal = null) {
    $sql = "SELECT id, descricao, valor, data, informacao FROM despesas WHERE user_id = '$userId' ";
    if ($dataInicial !== null && $dataFinal !== null) {
        $sql .= "AND data BETWEEN '$dataInicial' AND '$dataFinal' ";
    }
    $sql .= "ORDER BY data DESC";
    return $mysqli->query($sql);
}

$dataInicial = $_GET['dataInicial'] ?? null;
$dataFinal = $_GET['dataFinal'] ?? null;
$despesas = getDespesas($_SESSION['id'], $mysqli, $dataInicial, $dataFinal);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Ver Despesas</title>
    <link rel="stylesheet" type="text/css" href="stylesVerDespesas.css">
</head>
<body>
<nav class="menu-lateral"> <!-- menu lateral-->
    <div class="bt-expandir">
        <i class="bi bi-list" id="btn-exp"></i>
    </div>
    <ul>
        <li class="item-menu">
            <a href="../Usuario/painel.php">
                <span class="icon"><i class="bi bi-info-circle"></i></span>
                <span class="txt-link">Painel</span>
            </a>
        </li>
        <li class="item-menu">
            <a href="../Usuario/painel_despesas.php">
                <span class="icon"><i class="bi bi-piggy-bank"></i></span>
                <span class="txt-link">Despesas</span>
            </a>
        </li>
        <li class="item-menu">
            <a href="../Usuario/adicionar_despesa.php">
                <span class="icon"><i class="bi bi-plus-square"></i></span>
                <span class="txt-link">Entradas</span>
            </a>
        </li>
        <li class="item-menu ativo">
            <a href="../Usuario/ver_despesas.php">
                <span class="icon"><i class="bi bi-exposure"></i></span>
                <span class="txt-link">Excluir</span>
            </a>
        </li>
    </ul>
</nav> <!-- menu lateral-->

<!-- Foto do perfil para mostrar na página -->
<div class="profile-container">
    <?php if ($foto) : ?>
        <a href="#" id="profile-link" class="profile-link">
            <img src="../uploads/<?php echo htmlspecialchars($foto); ?>" alt="Foto do Perfil" class="profile-photo-small">
            <span class="username"><?php echo htmlspecialchars($primeiro_nome); ?></span>
        </a>
    <?php endif; ?>
</div>
<!-- Modal de perfil -->
<div id="profile-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p><a href="perfil.php" class="modal-link">Acessar Perfil</a></p>
        <p><a href="../Login/logout.php" id="close-modal" class="modal-link">Sair do Perfil</a></p>
    </div>
</div>
<!-- Foto do perfil para mostrar na página -->

<div class="container">
    <h1>Despesas</h1>
    <form method="GET" action="">
        <label for="dataInicial">Data Inicial:</label>
        <input type="date" id="dataInicial" name="dataInicial">
        <label for="dataFinal">Data Final:</label>
        <input type="date" id="dataFinal" name="dataFinal">
        <button type="submit">Filtrar</button>
        <a href="adicionar_despesa.php" class="btn_add_despesa">Adicionar Despesa</a>
    </form>
    <table id="despesasTable">
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Informação</th>
            <th>Ações</th>
        </tr>
        <?php while ($despesa = $despesas->fetch_assoc()): ?>
        <tr id="despesa_<?php echo $despesa['id']; ?>">
            <td><?php echo date('d/m/Y', strtotime($despesa['data'])); ?></td>
            <td><?php echo $despesa['descricao']; ?></td>
            <td>R$ <?php echo number_format($despesa['valor'], 2, ',', '.'); ?></td>
            <td><?php echo $despesa['informacao']; ?></td>
            <td>
                <!-- Botão de exclusão -->
                <button class="btn btn-delete" onclick="excluirDespesa(<?php echo $despesa['id']; ?>)">Excluir</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script src="script.js"></script>
<script>
    // Modal do Perfil
    document.getElementById("profile-link").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("profile-modal").style.display = "block";
    });

    document.getElementsByClassName("close")[0].addEventListener("click", function() {
        document.getElementById("profile-modal").style.display = "none";
    });

    // Menu Lateral
    document.getElementById("btn-exp").addEventListener("click", function() {
        document.getElementById("sidebar").style.width = "250px";
        document.getElementById("mainContent").style.marginLeft = "250px";
    });

    document.getElementById("closeBtn").addEventListener("click", function() {
        document.getElementById("sidebar").style.width = "0";
        document.getElementById("mainContent").style.marginLeft = "0";
    });
</script>
</body>
</html>
