<?php
include('../Login/protect.php');
include('../Login/conexao.php');
include('../Login/functions.php'); // Inclui o arquivo de funções

// Verificar se o perfil do usuário está completo
$user_id = $_SESSION['id'];
$sql_foto = "SELECT foto FROM usuarios WHERE id='$user_id'";
$result_foto = $mysqli->query($sql_foto);
$usuario_foto = $result_foto->fetch_assoc();
$foto = $usuario_foto['foto'] ?? '';

// Obtenha as últimas 5 despesas do usuário
$despesasMensais = getDespesasMensais($_SESSION['id'], $mysqli, 5); // Limita o número de resultados a 5
$totalDespesasMensais = getTotalDespesasMensais($_SESSION['id'], $mysqli);

$user_id = $_SESSION['id'];
$sql = "SELECT nome FROM usuarios WHERE id='$user_id'";
$result = $mysqli->query($sql);
$usuario = $result->fetch_assoc();
$primeiro_nome = explode(' ', $usuario['nome'])[0];

// Obtenha o salário líquido do usuário
$sql_salario = "SELECT salario_liquido FROM usuarios WHERE id='$user_id'";
$result_salario = $mysqli->query($sql_salario);
$usuario_salario = $result_salario->fetch_assoc();
$salario_liquido = $usuario_salario['salario_liquido'];

// Calcule a diferença entre o salário líquido e as despesas totais
$diferenca = $salario_liquido - $totalDespesasMensais;
echo "<script>console.log('Salário Líquido: " . $salario_liquido . "');</script>";
echo "<script>console.log('Total de Despesas Mensais: " . $totalDespesasMensais . "');</script>";


// Calcule o total mensal e a média mensal dos últimos 5 meses
$total_mensal = [];
for ($i = 0; $i < 5; $i++) {
    $mes = date('Y-m', strtotime("-$i months"));
    $sql_total_mensal = "SELECT SUM(valor) as total FROM despesas WHERE user_id='$user_id' AND DATE_FORMAT(data, '%Y-%m') = '$mes'";
    $result_total_mensal = $mysqli->query($sql_total_mensal);
    $row_total_mensal = $result_total_mensal->fetch_assoc();
    $total_mensal[$mes] = $row_total_mensal['total'] ?? 0;
}

$media_mensal = array_sum($total_mensal) / count($total_mensal);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard de Despesas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <style>
        .maintenance {
    width: 100%;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #f9f9f9;
    text-align: center;
}

.maintenance img {
    width: 1200px;
    height: auto;
}

.maintenance p {
    font-size: 20px;
    color: #333;
}

    </style>
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
        <li class="item-menu ativo">
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



<div class="container">
        <h1>Dashboard de Despesas</h1>
        <div class="row">
            <div class="col-md-6">
                <canvas id="despesasMensaisChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="mediaMensalChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="diferencaChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="distribuicaoDespesasChart"></canvas>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
 <script>

const ctxMensais = document.getElementById('despesasMensaisChart').getContext('2d');
        const despesasMensaisChart = new Chart(ctxMensais, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($total_mensal)); ?>,
                datasets: [{
                    label: 'Despesas Mensais',
                    data: <?php echo json_encode(array_values($total_mensal)); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxMedia = document.getElementById('mediaMensalChart').getContext('2d');
        const mediaMensalChart = new Chart(ctxMedia, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_keys($total_mensal)); ?>,
                datasets: [{
                    label: 'Média Mensal',
                    data: <?php echo json_encode(array_fill(0, count($total_mensal), $media_mensal)); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxDiferenca = document.getElementById('diferencaChart').getContext('2d');
        const diferencaChart = new Chart(ctxDiferenca, {
            type: 'bar',
            data: {
                labels: ['Salário Líquido', 'Despesas Mensais'],
                datasets: [{
                    label: 'Diferença',
                    data: [<?php echo $salario_liquido; ?>, <?php echo $totalDespesasMensais; ?>],
                    backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxDistribuicao = document.getElementById('distribuicaoDespesasChart').getContext('2d');
        const distribuicaoDespesasChart = new Chart(ctxDistribuicao, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($despesasCategorias); ?>,
                datasets: [{
                    label: 'Distribuição de Despesas',
                    data: <?php echo json_encode($despesasValores); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Manipulação do modal
        var modal = document.getElementById("profile-modal");
        var link = document.getElementById("profile-link");
        var span = document.getElementsByClassName("close")[0];

        link.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

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
</body>
</html>
