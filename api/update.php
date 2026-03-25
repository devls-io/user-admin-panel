<?php 
require_once '../config/connection.php';
require_once '../helpers/response.php';
require_once __DIR__ . '/../data/get_user.php';

try {

    $pdo = connectToDb();
    $id = trim($_POST['id'] ?? null);
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
        sendJson(['sucesso' => false, 'erro' => 'ID inválido para atualização'], 400);
        exit;
    }

    $dadosAtuais = listUserById($pdo , $id);

    if(!$dadosAtuais){
        sendJson(['sucesso'=> false , 'erro'=> 'Não foi possivel localizar o usuário'], 404);
        exit;
    }

    $imagemAntiga = $dadosAtuais['image'];
    $imagem_para_banco = $imagemAntiga;
    $trocouImagem = false ; // variável para controlar se o usuário trocou ou não sua foto.

    
 

    if (empty($nome)) {
        sendJson(['sucesso' => false, 'erro' => 'O nome não pode estar vazio'], 400);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJson(['sucesso' => false, 'erro' => 'Formato de e-mail inválido'], 400);
        exit;
    }

    // Lógica para atualizar a imagem

    if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK){
        $arquivo = $_FILES['avatar'];
        $extensao = strtolower(pathinfo($arquivo['name'] , PATHINFO_EXTENSION));
        $permitidos = ['jpg' , 'jpeg' , 'png' , 'webp'];

         if(!in_array($extensao, $permitidos)){
            sendJson(['sucesso'=> false , 'erro'=> 'Formato inválido! use JPG, PNG, JPEG ou WebP.'], 400);
            exit;
        }

         if($arquivo['size'] > 2 * 1024 * 1024){
            sendJson(['sucesso'=> false, 'erro'=> 'Imagem muito grande! Limite de 2MB'], 400);
            exit;
        }

        $novo_nome = uniqid('avatar_') . '.' . $extensao;
        $destino = __DIR__ . '/../assets/uploads/avatars/' . $novo_nome;

        if(move_uploaded_file($arquivo['tmp_name'] , $destino)){
            $imagem_para_banco = $novo_nome;
            $trocouImagem = true;
        }



    }

   
    $sql = "UPDATE usuarios SET nome = :nome, email = :email,image = :image WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':image', $imagem_para_banco);

    $stmt->execute();

    if($trocouImagem && $imagemAntiga !== 'default-avatar.png'){
        $caminhoAntigo = __DIR__ . '/../assets/uploads/avatars/' . $imagemAntiga;

        if(file_exists($caminhoAntigo)){
            unlink($caminhoAntigo);
        }
    }

   if ($stmt->rowCount() > 0) {
    sendJson([
        'sucesso' => true,
        'mensagem' => 'Registro atualizado!'
    ], 200);
} else {
    sendJson([
        'sucesso' => false,
        'erro' => 'Nenhuma alteração foi realizada nos dados.'
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