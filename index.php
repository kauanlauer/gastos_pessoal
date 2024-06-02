<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos de email e senha foram preenchidos
    if (empty($_POST['email'])) {
        $error_message = "Preencha seu e-mail";
    } elseif (empty($_POST['senha'])) {
        $error_message = "Preencha sua senha";
    } else {
        // Limpa e valida os dados de entrada
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        // Consulta SQL para verificar o usuário
        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        // Verifica se encontrou um usuário com as credenciais fornecidas
        if ($sql_query->num_rows == 1) {
            // Obtém os dados do usuário
            $usuario = $sql_query->fetch_assoc();

            // Inicia a sessão
            if (!isset($_SESSION)) {
                session_start();
            }

            // Armazena os dados do usuário na sessão
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            // Redireciona para a página de painel
            header("Location: painel.php");
            exit;
        } else {
            $error_message = "Falha ao logar! E-mail ou senha incorretos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Acesse sua conta</h1>
        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <p>
                <label for="email">E-mail</label>
                <input type="text" name="email" id="email">
            </p>

            <p>
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha">
            </p>

            <p>
                <button type="submit">Entrar</button>
            </p>
        </form>
        <!-- Botão de cadastro -->
        <p>Não possui uma conta? <a href="cadastro.php">Crie uma agora!</a></p>
    </div>
</body>
</html>

