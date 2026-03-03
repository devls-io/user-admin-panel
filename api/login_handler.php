<?php 
session_start();
require_once "../config/connection.php";
require_once '../helpers/response.php';

try{
    $jsonRecebido = file_get_contents("php://input");
    $dados = json_decode($jsonRecebido);

    $email = trim($dados->email ?? "");
    $senha = trim($dados->senha ?? "");

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        sendJson(['sucesso'=> false , 'erro'=> 'Formato de e-mail inválido'], 400);
        exit;
    }

    if(empty($senha)){
        sendJson(['sucesso'=> false, 'erro'=> 'senha inválida'], 400);
        exit;
    }

    
    $pdo = connectToDb();

    // Buscar os dados no banco

    $sql = "SELECT * FROM admins WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $adminData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o admin existe e se a senha bate

    if(!$adminData || !password_verify($senha, $adminData['senha'])){
        sendJson(['sucesso'=> false, 'erro'=> 'E-mail ou senha incorretos'], 401);
        exit;
    }

    // Se o login deu certo, abrimos uma sessão
    $_SESSION['admin_id'] = $adminData['id'];
    $_SESSION['admin_nome'] = $adminData['nome'];

    sendJson(['sucesso'=> true, 'mensagem'=> 'Bem vindo ' . $adminData['nome']]);

} catch (PDOException $e) {
    sendJson(['sucesso'=> false , 'erro'=> 'Erro no servidor ' . $e->getMessage()], 500);
}


?>