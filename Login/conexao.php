<?php
$usuario = 'id22260352_root';  // Atualize o usuário
$senha = 'Kauan3396@';     // Atualize a senha (coloque a senha fornecida)
$database = 'id22260352_login'; // Atualize o nome do banco de dados
$host = 'localhost';           // Atualize o host se necessário (normalmente 'localhost')

$mysqli = new mysqli($host, $usuario, $senha, $database);

if ($mysqli->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->connect_error);
}
?>
