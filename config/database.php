<?php

$host = '127.0.0.1';
$port = '3306';          // 🔴 porta correta
$db   = 'juridico';
$user = 'root';
$pass = '1234';              // coloque a senha se tiver
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
