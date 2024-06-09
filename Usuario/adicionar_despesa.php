<?php
// Ativar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../Login/conexao.php');
include('../Login/protect.php');
include('../Login/functions.php'); // Inclui o arquivo de funções

$error_message = "";

// Verificar se o perfil do usuário está completo
$user_id = $_SESSION['id'];
$sql_foto = "SELECT foto FROM usuarios WHERE id='$user_id'";
$result_foto = $mysqli->query($sql_foto);
$usuario_foto = $result_foto->fetch_assoc();
$foto = $usuario_foto['foto'] ?? '';

$sql_check_profile = "SELECT perfil_completo FROM usuarios WHERE id = '$user_id'";
$result_check_profile = $mysqli->query($sql_check_profile);

$perfil_completo = false; // Definir como falso por padrão

if ($result_check_profile && $result_check_profile->num_rows > 0) {
    $row = $result_check_profile->fetch_assoc();
    $perfil_completo = $row['perfil_completo'];
}

$sql = "SELECT nome FROM usuarios WHERE id='$user_id'";
$result = $mysqli->query($sql);
$usuario = $result->fetch_assoc();
$primeiro_nome = explode(' ', $usuario['nome'])[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['descricao']) || empty($_POST['valor']) || empty($_POST['data'])) {
        $error_message = "Por favor, preencha todos os campos.";
    } else {
        $descricao = $mysqli->real_escape_string($_POST['descricao']);
        $valor = $mysqli->real_escape_string($_POST['valor']);
        $data = $mysqli->real_escape_string($_POST['data']);
        $informacao = isset($_POST['informacao']) ? $mysqli->real_escape_string($_POST['informacao']) : '';
        $outra_descricao = isset($_POST['outra_descricao']) ? $mysqli->real_escape_string($_POST['outra_descricao']) : '';

        if ($descricao == 'Outro' && !empty($outra_descricao)) {
            $descricao = $outra_descricao;
        }

        $sql = "INSERT INTO despesas (user_id, descricao, valor, data, informacao) VALUES ('$user_id', '$descricao', '$valor', '$data', '$informacao')";

        if ($mysqli->query($sql)) {
            header("Location: ../Usuario/adicionar_despesa.php");
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Adicionar Despesa</title>
    <link rel="stylesheet" type="text/css" href="stylesAddDespesas.css">
    <style>
        
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
            <a href="../Usuario/adicionar_despesa.php">
                <span class="icon"><i class="bi bi-plus-square"></i></span>
                <span class="txt-link">Entradas</span>
            </a>
        </li>
        <li class="item-menu">
            <a href="../Usuario/ver_despesas.php">
                <span class="icon"><i class="bi bi-exposure"></i></span>
                <span class="txt-link">Excluir</span>
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

<div class="container"> <!-- Formulario para adicionar produtos-->
    <h1>Adicionar Despesa</h1>
    <?php if (!empty($error_message)) : ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <p>
            <label for="descricao">Descrição</label>
            <select name="descricao" id="descricao">
                <option value="Aluguel">Aluguel</option>
                <option value="Água">Água</option>
                <option value="Luz">Luz</option>
                <option value="Internet">Internet</option>
                <option value="Gás">Gás</option>
                <option value="Faculdade">Faculdade</option>
                <option value="Veículo">Veículo</option>
                <option value="Emprestimo">Emprestimo</option>
                <option value="Cartão de Credito">Cartão de Credito</option>
                <option value="Salário Líquido">Salário Líquido</option>
                <option value="Outro">Outros</option>
            </select>
            <input type="text" name="outra_descricao" id="outra_descricao" placeholder="Descreva a despesa" style="display:none;">
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
</div> <!-- Formulario para adicionar produtos-->

<script>
document.addEventListener("DOMContentLoaded", function() {
    var hasSubmenuItems = document.querySelectorAll('.has-submenu');

    hasSubmenuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            var submenu = this.nextElementSibling;
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
            }
        });
    });

    document.getElementById('descricao').addEventListener('change', function() {
        var outraDescricao = document.getElementById('outra_descricao');
        if (this.value === 'Outro') {
            outraDescricao.style.display = 'block';
        } else {
            outraDescricao.style.display = 'none';
        }
    });

    // ----------------------- Modal do Perfil -------------------------------------------
    // Função para abrir o modal do perfil
    function openProfileModal() {
        var profileModal = document.getElementById('profile-modal');
        profileModal.style.display = 'block';
    }

    // Função para fechar o modal do perfil
    function closeProfileModal() {
        var profileModal = document.getElementById('profile-modal');
        profileModal.style.display = 'none';
    }

    // Abre o modal do perfil ao clicar no link do perfil
    var profileLink = document.getElementById('profile-link');
    profileLink.addEventListener('click', function(event) {
        event.preventDefault();
        openProfileModal();
    });

    // Fecha o modal do perfil ao clicar no botão de fechar
    var closeModalButton = document.querySelector('.close');
    closeModalButton.addEventListener('click', function() {
        closeProfileModal();
    });

    // Fecha o modal do perfil ao clicar fora do conteúdo do modal
    window.addEventListener('click', function(event) {
        var profileModal = document.getElementById('profile-modal');
        if (event.target === profileModal) {
            closeProfileModal();
        }
    });

    // Fecha o modal do perfil ao clicar no link de sair do perfil
    var closeModalLink = document.getElementById('close-modal');
    closeModalLink.addEventListener('click', function() {
        closeProfileModal();
    });
    // ----------------------- Modal do Perfil -------------------------------------------
});
</script>

</body>
</html>
