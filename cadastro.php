<?php
include('conexao.php');

// Inicializa a variável de mensagem de erro
$error_message = '';

// Verifica se o formulário de cadastro foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos foram preenchidos
    if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
        $error_message = "Por favor, preencha todos os campos.";
    } else {
        // Limpa e valida os dados de entrada
        $nome = $mysqli->real_escape_string($_POST['nome']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        // Insere o novo usuário no banco de dados
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
    if ($mysqli->query($sql)) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $mysqli->error;
    }
}


        // Verifica se o e-mail já está cadastrado
        $sql_check_email = "SELECT * FROM usuarios WHERE email = '$email'";
        $result_check_email = $mysqli->query($sql_check_email);
        if ($result_check_email->num_rows > 0) {
            $error_message = "E-mail já cadastrado. Por favor, use outro e-mail.";
        } else {
            // Insere o novo usuário no banco de dados
            $sql_insert_user = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
            if ($mysqli->query($sql_insert_user)) {
                // Redireciona para a página de login após o cadastro bem-sucedido
                header("Location: index.php");
                exit;
            } else {
                $error_message = "Falha ao cadastrar usuário. Por favor, tente novamente.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Usuário</h1>
        <?php if (!empty($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <p>
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome">
            </p>
            <p>
                <label for="email">E-mail</label>
                <input type="text" name="email" id="email">
            </p>
            <p>
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha">
            </p>
            <p>
                <button type="submit">Cadastrar</button>
                <a href="index.php" class="button">Voltar</a>
            </p>
        </form>
    </div>
</body>
</html>
