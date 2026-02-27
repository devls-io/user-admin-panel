<?php 
require_once '../config/connection.php';
require_once '../helpers/response.php';

try {
    $jsonRecebido = file_get_contents("php://input");
    $dados = json_decode($jsonRecebido);

   
    $id    = $dados->id ?? null;
    $nome  = trim($dados->nome ?? '');
    $email = trim($dados->email ?? '');

    
    if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
        sendJson(['sucesso' => false, 'erro' => 'ID inválido para atualização'], 400);
        exit;
    }

    if (empty($nome)) {
        sendJson(['sucesso' => false, 'erro' => 'O nome não pode estar vazio'], 400);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJson(['sucesso' => false, 'erro' => 'Formato de e-mail inválido'], 400);
        exit;
    }

   
    $pdo = connectToDb();

   
    $sql = "UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);

    $stmt->execute();

   if ($stmt->rowCount() > 0) {
    sendJson([
        'sucesso' => true,
        'mensagem' => 'Registro atualizado!'
    ], 200);
} else {
    // Se caiu aqui, nada foi alterado (ou o ID não existe, ou os dados eram idênticos)
    sendJson([
        'sucesso' => false,
        'error' => 'Usuário não encontrado ou nenhuma alteração realizada.'
    ], 404); 
}
    
} catch (PDOException $e) {
    error_log("Erro de banco no Update (ID $id): " . $e->getMessage(), 3, __DIR__ . '/../logs_do_sistema.txt');

    // Aquela inteligência para e-mail duplicado de novo!
    $msgErro = (str_contains($e->getMessage(), 'Duplicate entry')) 
                ? 'Este e-mail já pertence a outro usuário.' 
                : 'Erro interno no servidor ao atualizar.';

    sendJson(['sucesso' => false, 'erro' => $msgErro], 500);

} catch (Exception $e) {
    error_log("Erro inesperado no Update: " . $e->getMessage(), 3, __DIR__ . '/../logs_do_sistema.txt');
    sendJson(['sucesso' => false, 'erro' => 'Ocorreu um erro inesperado'], 500);
}