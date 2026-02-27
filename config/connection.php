<?php

// Autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Carrega as variáveis do seu arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

function connectToDb(){
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $db =   $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
};
?>