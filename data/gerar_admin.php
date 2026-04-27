<!-- Arquivo apenas para uso local -->
<?php 
require_once '../config/connection.php';
$pdo = connectToDb();

// basta mudar os dados abaixo para criar um novo admin
$nome = "nomeAdmin"; 
$email = "email@email.com";
$senha_pura = "senhaAdmin";

$senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

try{
    $sql = "INSERT INTO admins (nome,email,senha) VALUES (:nome , :email , :senha)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome' , $nome);
    $stmt->bindParam(':email' , $email);
    $stmt->bindParam(':senha', $senha_hash);

    $stmt->execute();

    echo "✅ Admin criado com sucesso!";
    
} catch (PDOException $e) {
    echo "❌ Erro ao criar admin: " . $e->getMessage();
}
?>