<?php
include('protect.php');
include('conexao.php');

$user_id = $_SESSION['id'];
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $cpf = $mysqli->real_escape_string($_POST['cpf']);
    $data_nascimento = $mysqli->real_escape_string($_POST['data_nascimento']);
    $salario_liquido = $mysqli->real_escape_string($_POST['salario_liquido']);
    $biografia = $mysqli->real_escape_string($_POST['biografia']);
    $descricao = $mysqli->real_escape_string($_POST['descricao']);
    
    // Manipulando o upload da foto
    $foto_nome = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $foto_nome = uniqid() . "-" . basename($_FILES["foto"]["name"]);
        $target_file = "uploads/" . $foto_nome;
        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $error_message = "Erro ao fazer upload da foto.";
        }
    }

    if (empty($error_message)) {
        $sql = "UPDATE usuarios SET nome='$nome', cpf='$cpf', data_nascimento='$data_nascimento', salario_liquido='$salario_liquido', biografia='$biografia', descricao='$descricao'";
        if ($foto_nome) {
            $sql .= ", foto='$foto_nome'";
        }
        $sql .= " WHERE id='$user_id'";

        if ($mysqli->query($sql)) {
            $success_message = "Perfil atualizado com sucesso!";
        } else {
            $error_message = "Erro ao atualizar perfil: " . $mysqli->error;
        }
    }
}

$sql = "SELECT nome, email, cpf, data_nascimento, salario_liquido, biografia, descricao, foto FROM usuarios WHERE id='$user_id'";
$result = $mysqli->query($sql);

if ($result) {
    $usuario = $result->fetch_assoc();
} else {
    $error_message = "Erro ao carregar perfil: " . $mysqli->error;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Pessoal</title>
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
        input[type="text"], input[type="email"], input[type="date"], input[type="number"], textarea {
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
        .success-message {
            color: green;
            text-align: center;
        }
        img.profile-photo {
            display: block;
            max-width: 150px;
            margin: 20px auto;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="painel.php">Home</a>
        <a href="perfil.php">Perfil</a>
        <a href="logout.php">Sair</a>
    </div>
    <div class="container">
        <h1>Perfil Pessoal</h1>
        <?php if (!empty($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)) : ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if (isset($usuario)) : ?>
        <form action="perfil.php" method="POST" enctype="multipart/form-data">
            <p>
                <label for="nome">Nome Completo</label>
                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>">
            </p>
            <p>
                <label for="email">E-mail</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
            </p>
            <p>
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" value="<?php echo htmlspecialchars($usuario['cpf']); ?>">
            </p>
            <p>
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento']); ?>">
            </p>
            <p>
                <label for="salario_liquido">Salário Líquido</label>
                <input type="number" step="0.01" name="salario_liquido" id="salario_liquido" value="<?php echo htmlspecialchars($usuario['salario_liquido']); ?>">
            </p>
            <p>
                <label for="biografia">Biografia Pessoal</label>
                <textarea name="biografia" id="biografia"><?php echo htmlspecialchars($usuario['biografia']); ?></textarea>
            </p>
            <p>
                <label for="descricao">Descrição do Perfil</label>
                <textarea name="descricao" id="descricao"><?php echo htmlspecialchars($usuario['descricao']); ?></textarea>
            </p>
            <p>
                <label for="foto">Foto do Perfil</label>
                <input type="file" name="foto" id="foto">
            </p>
            <?php if ($usuario['foto']) : ?>
                <p>
                    <img src="uploads/<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto do Perfil" class="profile-photo">
                </p>
            <?php endif; ?>
            <p>
                <button type="submit">Atualizar Perfil</button>
            </p>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
