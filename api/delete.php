<?php 
require_once '../config/connection.php';
require_once '../helpers/response.php';

try{
    $jsonRecebido = file_get_contents("php://input");
    $dados = json_decode($jsonRecebido);
    $idParaExcluir = $dados->id_para_excluir ?? null;

    // Validação Tem ID e é um número inteiro?

    if(!$idParaExcluir || !filter_var($idParaExcluir, FILTER_VALIDATE_INT )){
        sendJson(['sucesso'=> false, 'erro'=> 'ID inválido ou não fornecido'], 400);
        exit; // <--- ID lixo = interrompido
    };

    $pdo = connectToDb();

    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $idParaExcluir);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
    sendJson([
        'sucesso' => true,
        'mensagem' => 'Registro deletado com sucesso!'
    ], 200);
} else {
    // Se caiu aqui, o ID não existia no banco
    sendJson([
        'sucesso' => false,
        'erro' => 'Usuário não encontrado ou já deletado.'
    ], 404); 
}

} catch(PDOException $e){
    // banco deu erro, registramos log tecnico
    error_log("Erro ao deletar (ID $idParaExcluir): " . $e->getMessage(), 3, __DIR__ . '/../logs_do_sistema.txt');

    sendJson(['sucesso'=> false,'erro'=>'Erro interno ao processar a exclusão'], 500);

} catch(Exception $e){
    // Qualquer outro erro que não seja banco
    error_log("Erro inesperado no Delete: " . $e->getMessage(), 3, __DIR__ . '/../logs_do_sistema.txt');
    sendJson(['sucesso'=>false,'erro'=>'Ocorreu um erro inesperado'], 500);
}