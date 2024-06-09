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

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Painel do Usuário</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="stylesPainel.css">
</head>
<body>


<nav class="menu-lateral"> <!-- menu lateral-->
<div class="bt-expandir">
    <i class="bi bi-list" id="btn-exp"></i>
</div>

    <ul>
        <li class="item-menu ativo">
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
 
<div class="maintenance"> <!-- Em manutenção -->
    <img src="../images/image.gif" alt="Site em manutenção" />
    <p>Desculpe pelo transtorno. Nosso site está atualmente em manutenção.</p>
</div>  <!-- Em manutenção -->



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


            // Preparar dados para o gráfico
            var ctx = document.getElementById('comparacaoChart').getContext('2d');
            var salarioLiquido = <?php echo json_encode($salario_liquido); ?>;
            var totalDespesasMensais = <?php echo json_encode($totalDespesasMensais); ?>;

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Salário Líquido', 'Total de Despesas'],
                    datasets: [{
                        label: 'Comparação Financeira',
                        data: [salarioLiquido, totalDespesasMensais],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
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
    </script>
</body>
</html>
