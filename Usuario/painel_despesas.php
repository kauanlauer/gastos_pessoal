<?php
include('../Login/conexao.php');
include('../Login/protect.php');

$user_id = $_SESSION['id'];

// Verificar se o perfil do usuário está completo
$user_id = $_SESSION['id'];
$sql_foto = "SELECT foto FROM usuarios WHERE id='$user_id'";
$result_foto = $mysqli->query($sql_foto);
$usuario_foto = $result_foto->fetch_assoc();
$foto = $usuario_foto['foto'] ?? '';


$user_id = $_SESSION['id'];
$sql = "SELECT nome FROM usuarios WHERE id='$user_id'";
$result = $mysqli->query($sql);
$usuario = $result->fetch_assoc();
$primeiro_nome = explode(' ', $usuario['nome'])[0];


// Buscar dados dos últimos 12 meses
$sql = "SELECT 
            MONTH(data) AS mes, 
            YEAR(data) AS ano, 
            SUM(CASE WHEN descricao = 'Salario Liquido' THEN valor ELSE 0 END) AS total_entradas,
            SUM(CASE WHEN descricao != 'Salario Liquido' THEN valor ELSE 0 END) AS total_despesas
        FROM despesas 
        WHERE user_id = '$user_id' AND data >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY YEAR(data), MONTH(data)
        ORDER BY YEAR(data) DESC, MONTH(data) DESC";

$result = $mysqli->query($sql);
$monthly_data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $monthly_data[] = $row;
    }
}

// Buscar pedidos do mês atual
$current_month_start = date('Y-m-01');
$current_month_end = date('Y-m-t');

$sql_current_month = "SELECT descricao, valor, data FROM despesas WHERE user_id = '$user_id' AND data BETWEEN '$current_month_start' AND '$current_month_end'";
$result_current_month = $mysqli->query($sql_current_month);
$current_month_expenses = [];

if ($result_current_month) {
    while ($row = $result_current_month->fetch_assoc()) {
        $current_month_expenses[] = $row;
    }
}

// Calcular médias mensais
$total_expenses = array_sum(array_column($monthly_data, 'total_despesas'));
$total_months = count($monthly_data);
$average_expenses = $total_months > 0 ? $total_expenses / $total_months : 0;

$total_entries = array_sum(array_column($monthly_data, 'total_entradas'));
$average_entries = $total_months > 0 ? $total_entries / $total_months : 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Painel de Despesas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="painelDespesa.css">
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
        <li class="item-menu ativo">
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
        <li class="item-menu">
            <a href="../Usuario/ver_despesas.php">
                <span class="icon"><i class="bi bi-exposure"></i></span>
                <span class="txt-link">Excluir </span>
            </a>
        </li>
        <li class="item-menu">
            <a href="dashboard.php">
                <span class="icon"><i class="bi bi-columns-gap"></i></i></span>
                <span class="txt-link">Dashboard</span>
            </a>
        </li>
        <li class="item-menu">
            <a href="../Grupos/grupos.php">
                <span class="icon"><i class="bi bi-people-fill"></i></span>
                <span class="txt-link">Grupos</span>
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

<div class="container"> <!--painel principal-->
        <h1>Bem-vindo, <?php echo htmlspecialchars($primeiro_nome); ?>!</h1>
</div>
<div class="container"> <!-- Painel de despesas -->
    <h1>Painel de Despesas</h1>

    <h2>Resumo dos Últimos 12 Meses</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Mês/Ano</th>
                <th>Entradas (Salário Líquido)</th>
                <th>Despesas</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monthly_data as $data) : ?>
                <tr>
                    <td><?php echo $data['mes'] . '/' . $data['ano']; ?></td>
                    <td><?php echo number_format($data['total_entradas'], 2, ',', '.'); ?></td>
                    <td><?php echo number_format($data['total_despesas'], 2, ',', '.'); ?></td>
                    <td><?php echo number_format($data['total_entradas'] - $data['total_despesas'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Pedidos do Mês Atual</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($current_month_expenses as $expense) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($expense['descricao']); ?></td>
                    <td><?php echo number_format($expense['valor'], 2, ',', '.'); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($expense['data'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Médias Mensais</h2>
    <p>Despesa Média Mensal: <?php echo number_format($average_expenses, 2, ',', '.'); ?></p>
    <p>Entrada Média Mensal: <?php echo number_format($average_entries, 2, ',', '.'); ?></p>

    <canvas id="myChart"></canvas>
</div> <!-- Painel de despesas -->
<script src="script.js"></script>
<script>

// ----------------------- Modal do Perfil -------------------------------------------
// Função para abrir o modal ao clicar na foto de perfil
document.getElementById("profile-link").addEventListener("click", function(event) {
    event.preventDefault(); // Impede o comportamento padrão do link
    document.getElementById("profile-modal").style.display = "block"; // Exibe o modal
});

// Função para fechar o modal ao clicar no "X"
document.getElementsByClassName("close")[0].addEventListener("click", function() {
    document.getElementById("profile-modal").style.display = "none"; // Oculta o modal
});

// ----------------------- Modal do Perfil -------------------------------------------

        document.getElementById("openBtn").addEventListener("click", function() {
    document.getElementById("sidebar").style.width = "250px";
    document.getElementById("mainContent").style.marginLeft = "250px";
});

document.getElementById("closeBtn").addEventListener("click", function() {
    document.getElementById("sidebar").style.width = "0";
    document.getElementById("mainContent").style.marginLeft = "0";
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('myChart').getContext('2d');
    var chartData = <?php echo json_encode($monthly_data); ?>;
    var labels = chartData.map(data => `${data.mes}/${data.ano}`);
    var entriesData = chartData.map(data => parseFloat(data.total_entradas));
    var expensesData = chartData.map(data => parseFloat(data.total_despesas));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Entradas (Salário Líquido)',
                    data: entriesData,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 255, 0, 0.1)',
                    fill: true,
                },
                {
                    label: 'Despesas',
                    data: expensesData,
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.1)',
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
</body>
</html>
