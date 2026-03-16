<?php 
require_once '../config/connection.php';
require_once '../helpers/response.php';

try{
    // Receber dados pelo FormData em vez de JSON

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $imagem_nome = 'default-avatar.png'; // Padrão inicialmente


    if(empty($nome)){
        sendJson(['sucesso'=> false, 'erro'=> 'Nome inválido'], 400);
        exit;
    };

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        sendJson(['sucesso'=> false, 'erro'=> 'Formato de e-mail inválido'], 400);
        exit;
    };

    // Lógica para upload de imagem
    if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK){
        $arquivo = $_FILES['avatar'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg' , 'jpeg' , 'png' , 'webp'];

        if(!in_array($extensao, $permitidos)){
            sendJson(['sucesso'=> false , 'erro'=> 'Formato inválido! use JPG, PNG, JPEG ou WebP.'], 400);
            exit;
        }

        if($arquivo['size'] > 2 * 1024 * 1024){
            sendJson(['sucesso'=> false, 'erro'=> 'Imagem muito grande! Limite de 2MB'], 400);
            exit;
        }

        // Processamento da Imagem
        $novo_nome = uniqid('avatar_') . '.' . $extensao;
        $destino = __DIR__ . '/../assets/uploads/avatars/' . $novo_nome;

        if(move_uploaded_file($arquivo['tmp_name'], $destino)){
            $imagem_nome = $novo_nome;
        } else {
            sendJson(['sucesso'=> false, 'erro'=> 'Falha ao salvar a imagem no servidor.'], 500);
            exit;
        }

        
    }

    // Conexão e Comandos para o Banco
    $pdo = connectToDb();

    $sql = "INSERT INTO usuarios (nome, email, image) VALUES (:nome, :email, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':image' , $imagem_nome);
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