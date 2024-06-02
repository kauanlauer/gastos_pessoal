<?php
include('protect.php');
include('conexao.php');

// Função para obter todos os grupos do usuário
function getGrupos($usuario_id, $mysqli) {
    $sql = "SELECT * FROM grupos WHERE id IN (SELECT grupo_id FROM usuario_grupo WHERE usuario_id = '$usuario_id')";
    return $mysqli->query($sql);
}

// Função para obter as despesas de um grupo específico
function getDespesasGrupo($grupo_id, $mysqli) {
    $sql = "SELECT * FROM despesas WHERE grupo_id = '$grupo_id'";
    return $mysqli->query($sql);
}

// Verifica se o formulário de adicionar despesa foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $mysqli->real_escape_string($_POST['descricao']);
    $valor = $_POST['valor'];
    $grupo_id = $_POST['grupo_id'];

    $sql = "INSERT INTO despesas (descricao, valor, grupo_id) VALUES ('$descricao', '$valor', '$grupo_id')";

    if ($mysqli->query($sql) === TRUE) {
        echo "Nova despesa adicionada com sucesso!";
    } else {
        echo "Erro ao adicionar nova despesa: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos</title>
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
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container form label {
            display: block;
            margin-bottom: 5px;
        }
        .container form input[type="text"],
        .container form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .container form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .container form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .container form input[type="submit"]:hover {
            background-color: #45a049;
        }
        .grupo {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .grupo h2 {
            margin-top: 0;
        }
        .grupo .despesas {
            margin-top: 10px;
        }
        .grupo .despesas p {
            margin: 5px 0;
        }
        .grupo .botoes {
            margin-top: 10px;
        }
        .grupo .botoes button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-right: 10px;
        }
        .grupo .botoes button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="painel.php">Home</a>
        <a href="adicionar_despesa.php">Adicionar Despesa</a>
        <a href="ver_despesas.php">Ver Despesas</a>
        <a href="grupos.php">Grupos</a>
        <a href="logout.php">Sair</a>
    </div>
    <div class="container">
        <h1>Meus Grupos</h1>
        <div class="botoes">
            <button onclick="window.location.href='novo_grupo.php'">Criar Novo Grupo</button>
            <button onclick="window.location.href='grupos_existente.php'">Ver Grupos Existente</button>
        </div>
        <?php
        // Obtém todos os grupos do usuário
        $grupos = getGrupos($_SESSION['id'], $mysqli);

        if ($grupos !== false && $grupos->num_rows > 0) {
            // Loop through each group
            while ($grupo = $grupos->fetch_assoc()) {
                echo '<div class="grupo">';
                echo '<h2>' . $grupo['nome'] . '</h2>';
                echo '<p>' . $grupo['descricao'] . '</p>';

                // Obtém as despesas do grupo
                $despesasGrupo = getDespesasGrupo($grupo['id'], $mysqli);
                echo '<div class="despesas">';
                while ($despesa = $despesasGrupo->fetch_assoc()) {
                    echo '<p>' . $despesa['descricao'] . ' - R$ ' . number_format($despesa['valor'], 2, ',', '.') . '</p>';
                }
                echo '</div>'; // fechar div despesas
                echo '<form action="" method="POST">';
                echo '<input type="hidden" name="grupo_id" value="' . $grupo['id'] . '">';
                echo '<label for="descricao">Descrição:</label><br>';
                echo '<input type="text" id="descricao" name="descricao" required><br>';
                echo '<label for="valor">Valor:</label><br>';
                echo '<input type="number" id="valor" name="valor" required><br>';
                echo '<input type="submit" value="Adicionar Despesa">';
                echo '</form>';

                echo '</div>'; // fechar div grupo
            }
        } else {
            echo "<p>Você não está em nenhum grupo ainda.</p>";
        }
        ?>
    </div>
</body>
</html>

               
