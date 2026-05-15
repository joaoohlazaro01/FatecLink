<?php
$host = 'localhost';
$dbname = 'fatec_estagios';
$user = 'root'; // Usuário padrão do XAMPP/WAMP
$pass = '';     // Senha padrão vazia no XAMPP/WAMP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configura o PDO para lançar exceções em caso de erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
