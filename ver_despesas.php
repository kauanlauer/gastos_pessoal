<?php
include('protect.php');
include('conexao.php');

function getDespesas($userId, $mysqli) {
    $sql = "SELECT id, descricao, valor, data, informacao FROM despesas WHERE user_id = '$userId' ORDER BY data DESC";
    return $mysqli->query($sql);
}

function editarDespesa($id, $novaDescricao, $novoValor, $novaData, $novaInformacao, $mysqli) {
    $id = $mysqli->real_escape_string($id);
    $novaDescricao = $mysqli->real_escape_string($novaDescricao);
    $novoValor = $mysqli->real_escape_string($novoValor);
    $novaData = $mysqli->real_escape_string($novaData);
    $novaInformacao = $mysqli->real_escape_string($novaInformacao);

    $sql = "UPDATE despesas SET descricao='$novaDescricao', valor='$novoValor', data='$novaData', informacao='$novaInformacao' WHERE id='$id'";
    return $mysqli->query($sql);
}

function excluirDespesa($id, $mysqli) {
    $id = $mysqli->real_escape_string($id);

    $sql = "DELETE FROM despesas WHERE id='$id'";
    return $mysqli->query($sql);
}

$despesas = getDespesas($_SESSION['id'], $mysqli);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Despesas</title>
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
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        .btn-editar, .btn-excluir {
            display: inline-block;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 5px;
        }
        .btn-excluir {
            background-color: #f44336;
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
        <h1>Despesas</h1>
        <table id="despesasTable">
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Informação</th>
                <th>Ações</th>
            </tr>
            <?php while ($despesa = $despesas->fetch_assoc()): ?>
            <tr id="despesa_<?php echo $despesa['id']; ?>">
                <td><?php echo date('d/m/Y', strtotime($despesa['data'])); ?></td>
                <td><?php echo $despesa['descricao']; ?></td>
                <td>R$ <?php echo number_format($despesa['valor'], 2, ',', '.'); ?></td>
                <td><?php echo $despesa['informacao']; ?></td>
                <td>
                    <button onclick="editarDespesa(<?php echo $despesa['id']; ?>)">Editar</button>
                    <button onclick="excluirDespesa(<?php echo $despesa['id']; ?>)">Excluir</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <script>
function editarDespesa(id) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "editar_despesa.php?id=" + id, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Atualizar a página ou mostrar uma mensagem de sucesso após a edição
        }
    };
    xhr.send();
}

function excluirDespesa(id) {
    if (confirm("Tem certeza que deseja excluir esta despesa?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "excluir_despesa.php?id=" + id, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Remover a linha da tabela após a exclusão
                var row = document.getElementById("despesa_" + id);
                row.parentNode.removeChild(row);
            }
        };
        xhr.send();
    }
}

    </script>
</body>
</html>