<?php
$usuario = 'gastopessoal';
$senha = 'Kauan3396@';
$database = 'gasto_pessoal';
$host = 'gasto-pessoal.mysql.uhserver.com';

$mysqli = new mysqli($host, $usuario, $senha, $database);

if ($mysqli->error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
} 
?>
