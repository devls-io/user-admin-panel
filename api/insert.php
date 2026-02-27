<?php 
require_once '../config/connection.php';
require_once '../helpers/response.php';

try{
    $jsonRecebido = file_get_contents("php://input");
    $dados = json_decode($jsonRecebido);

    // Limpeza Basica
    $nome = trim($dados->nome ?? '');
    $email = trim($dados->email ?? '');

    if(empty($nome)){
        sendJson(['sucesso'=> false, 'erro'=> 'Nome inválido'], 400);
        exit;
    };

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        sendJson(['sucesso'=> false, 'erro'=> 'Formato de e-mail inválido'], 400);
        exit;
    };

    $pdo = connectToDb();

    $sql = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    sendJson([
        'sucesso'=>true,
        'mensagem'=> 'Registro inserido com sucesso!'
    ], 201);
} catch(PDOException $e){
    // Erros como duplicação de email
    error_log("Erro de banco no insert: " . $e->getMessage(), 3 , __DIR__ . '/../logs_do_sistema.txt');

    $msgErro = (str_contains($e->getMessage(), 'Duplicate entry')) ? 'Este e-mail já está cadastrado' : 'Erro interno no servidor.';

    sendJson(['sucesso'=>false, 'erro'=> $msgErro], 500);

} catch(Exception $e){
    error_log("Erro inesperado no Insert: " . $e->getMessage(), 3, __DIR__ . '/../logs_do_sistema.txt');
    
    sendJson(['sucesso' => false, 'erro' => 'Ocorreu um erro inesperado'], 500);
}



?>