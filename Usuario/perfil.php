<?php
// Ativar exibição de erros
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

$sql = "SELECT nome FROM usuarios WHERE id='$user_id'";
$result = $mysqli->query($sql);
$usuario = $result->fetch_assoc();
$primeiro_nome = explode(' ', $usuario['nome'])[0];

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
        $target_file = "../uploads/" . $foto_nome;
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
            // Atualiza a coluna perfil_completo para 1
            $sql_update_profile = "UPDATE usuarios SET perfil_completo=1 WHERE id='$user_id'";
            if ($mysqli->query($sql_update_profile)) {
                $success_message = "Perfil atualizado com sucesso!";
            } else {
                $error_message = "Erro ao atualizar perfil completo: " . $mysqli->error;
            }
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Pessoal</title>
    <html lang="pt-br">
    <link rel="stylesheet" type="text/css" href="perfil.css">
    <style>
    </style>

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
            <a href="#">
                <span class="icon"><i class="bi bi-columns-gap"></i></i></span>
                <span class="txt-link">Dashboard</span>
            </a>
        </li>
        <li class="item-menu">
            <a href="#">
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
                <label for="foto">Escolha sua Foto de Perfil</label>
                <label class="custom-file-upload">
                    <input type="file" name="foto" id="foto">
                    Escolher arquivo .png .jpg .jpeg
                </label>
            </p>

            <?php if ($usuario['foto']) : ?>
                <p>
                    <img src="../uploads/<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto do Perfil" class="profile-photo">
                </p>
            <?php endif; ?>
            <p>
                <button type="submit">Atualizar Perfil</button>
            </p>
        </form>
        <?php endif; ?>
    </div>
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
</body>
</html>
