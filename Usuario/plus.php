<?php
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
        $target_file = "../uploads/" . $foto_nome;
        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $error_message = "Erro ao fazer upload da foto.";
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
    <html lang="pt-br">
    <link rel="stylesheet" type="text/css" href="../usuario/plus.css">
    <style>
    </style>
    
</head>

<body>
       <!-- Menu Lateral -->
<div class="sidebar" id="sidebar">
<a href="javascript:void(0)" class="closebtn" id="closeBtn">&times;</a>
    <a href="painel.php">Painel</a>
    <a href="adicionar_despesa.php">Adicionar Despesa</a>
    <a href="ver_despesas.php">Ver Despesas</a>
    <a href="../Grupos/grupos.php">Grupos</a>
</div>
<button class="openbtn" id="openBtn">Menu ☰</button>
<div class="main-content" id="mainContent">
    <!-- Conteúdo principal aqui -->
</div>
 <!-- Menu Lateral -->

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
        <p><a href="../Login/logout.php" id="close-modal" class="modal-link_sair">Sair do Perfil</a></p>
    </div>
</div>
<!-- Foto do perfil para mostrar na página -->

<div class="plan">
    <h2>Plano Grátis</h2>
    <p>Usufrua do Gasto Pessoal.</p>
    <button>Escolher</button>
</div>

<div class="plan">
    <h2>Plano Plus</h2>
    <p>Gasto Pessoal, Gráficos, Dashboards e Dicas Financeiras.</p>
    <button>Escolher</button>
</div>

<div class="plan">
    <h2>Plano Premium</h2>
    <p>Todos os recursos dos planos anteriores e Relatórios, Planilhas, PDF e Acesso Exclusivos.</p>
    <button>Escolher</button>
</div>



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
