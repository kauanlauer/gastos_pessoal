<?php
include('protect.php');
include('conexao.php');
include('functions.php'); // Inclui o arquivo de funções

$totalDespesasMensais = getTotalDespesasMensais($_SESSION['id'], $mysqli);
$despesasMensais = getDespesasMensais($_SESSION['id'], $mysqli);

$user_id = $_SESSION['id'];
$sql = "SELECT nome FROM usuarios WHERE id='$user_id'";
$result = $mysqli->query($sql);
$usuario = $result->fetch_assoc();
$primeiro_nome = explode(' ', $usuario['nome'])[0];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f4f4f4;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #0056b3;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            flex: 1;
            min-width: 250px;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card h2 {
            margin-top: 0;
        }
        .card p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="painel.php">Home</a>
        <a href="adicionar_despesa.php">Adicionar Despesa</a>
        <a href="ver_despesas.php">Ver Despesas</a>
        <a href="grupos.php">Grupos</a> <!-- Adicionado botão para acessar a página de grupos -->
        <a href="perfil.php">Perfil</a>
        <a href="logout.php">Sair</a>
    </div>
    <div class="container">
    <h1>Bem-vindo, <?php echo htmlspecialchars($primeiro_nome); ?>!</h1>
        <div class="dashboard">
            <div class="card">
                <h2>Resumo Mensal</h2>
                <p>Total de Despesas: R$ <?php echo number_format($totalDespesasMensais, 2, ',', '.'); ?></p>
            </div>
            <div class="card">
                <h2>Despesas Recentes</h2>
                <?php while ($despesa = $despesasMensais->fetch_assoc()): ?>
                    <p><?php echo date('d/m/Y', strtotime($despesa['data'])); ?>: <?php echo $despesa['descricao']; ?> - R$ <?php echo number_format($despesa['valor'], 2, ',', '.'); ?></p>
                <?php endwhile; ?>
            </div>
            <div class="card">
                <h2>Gráficos e Relatórios</h2>
                <p>Aqui você pode adicionar gráficos e relatórios financeiros.</p>
            </div>
        </div>
    </div>
</body>
</html>
