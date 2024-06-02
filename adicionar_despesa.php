<?php
include('conexao.php');
session_start(); // Certifique-se de que a sessão foi iniciada

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['descricao']) || empty($_POST['valor']) || empty($_POST['data']) || empty($_POST['informacao'])) {
        $error_message = "Por favor, preencha todos os campos.";
    } else {
        $descricao = $mysqli->real_escape_string($_POST['descricao']);
        $valor = $mysqli->real_escape_string($_POST['valor']);
        $data = $mysqli->real_escape_string($_POST['data']);
        $informacao = $mysqli->real_escape_string($_POST['informacao']);
        $user_id = $_SESSION['id']; // Supondo que você tenha armazenado o ID do usuário na sessão

        $sql = "INSERT INTO despesas (user_id, descricao, valor, data, informacao) VALUES ('$user_id', '$descricao', '$valor', '$data', '$informacao')";

        if ($mysqli->query($sql)) {
            header("Location: painel.php");
            exit;
        } else {
            $error_message = "Erro ao adicionar despesa: " . $mysqli->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Despesa</title>
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
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form p {
            margin: 20px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"], input[type="date"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="painel.php">Home</a>
        <a href="adicionar_despesa.php">Adicionar Despesa</a>
        <a href="ver_despesas.php">Ver Despesas</a>
        <a href="logout.php">Sair</a>
    </div>
    <div class="container">
        <h1>Adicionar Despesa</h1>
        <?php if (!empty($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <p>
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" id="descricao">
            </p>
            <p>
                <label for="valor">Valor</label>
                <input type="number" step="0.01" name="valor" id="valor">
            </p>
            <p>
                <label for="data">Data</label>
                <input type="date" name="data" id="data">
            </p>
            <p>
                <label for="informacao">Informações</label>
                <input type="text" name="informacao" id="informacao">
            </p>  
            <p>
                <button type="submit">Adicionar Despesa</button>
            </p>
        </form>
    </div>
</body>
</html>
