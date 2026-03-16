<?php 
require_once '../config/connection.php';
require_once '../helpers/response.php';
require_once __DIR__ . '/../data/get_user.php';

try{

    $pdo = connectToDb();
   
    $jsonRecebido = file_get_contents("php://input");
    $dados = json_decode($jsonRecebido);
    $idParaExcluir = $dados->id_para_excluir ?? null;

    if(!$idParaExcluir || !filter_var($idParaExcluir, FILTER_VALIDATE_INT )){
        sendJson(['sucesso'=> false, 'erro'=> 'ID inválido ou não fornecido'], 400);
        exit; // <--- ID lixo = interrompido
    };

    // Imagem do usuário a ser deletado
    $dadosUsuario = listUserById($pdo, $idParaExcluir);
    if (!$dadosUsuario) {
        sendJson(['sucesso' => false, 'erro' => 'Usuário não encontrado'], 404);
        exit;
    }
    $imagem = $dadosUsuario['image'];

  

    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $idParaExcluir);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {

     // Se o usuário foi removido, deletamos a imagem também

    if($imagem !== 'default-avatar.png'){
        $caminhoAntigo = __DIR__ . '/../assets/uploads/avatars/' . $imagem;

        if(file_exists($caminhoAntigo)){
            unlink($caminhoAntigo);
        }
    }
    
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