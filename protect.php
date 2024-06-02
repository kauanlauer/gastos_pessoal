<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}


// Verifica se o usuário está logado
if(!isset($_SESSION['id'])){
    echo "<!DOCTYPE html>";
    echo "<html lang=\"pt-BR\">";
    echo "<head>";
    echo "<meta charset=\"UTF-8\">";
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
    echo "<title>Acesso Negado</title>";
    echo "<style>";
    echo "body {";
    echo "  font-family: Arial, sans-serif;";
    echo "  display: flex;";
    echo "  justify-content: center;";
    echo "  align-items: center;";
    echo "  height: 100vh;";
    echo "  margin: 0;";
    echo "}";
    echo "button {";
    echo "  padding: 10px 20px;";
    echo "  background-color: #007bff;";
    echo "  color: #fff;";
    echo "  border: none;";
    echo "  cursor: pointer;";
    echo "  font-size: 16px;";
    echo "  border-radius: 5px;";
    echo "}";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    echo "<div>";
    echo "<p>Você não pode acessar essa página. Por favor, faça login para entrar!</p>";
    echo "<button><a href=\"index.php\" style=\"text-decoration: none; color: #fff;\">Entrar</a></button>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
    exit; // Encerra a execução do código PHP
}
?>
