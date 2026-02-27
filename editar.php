<?php 
require_once 'data/get_user.php';
require_once 'config/connection.php';

$id = $_GET['id'] ?? null;
$nome = "";
$email = "";

try{
    $pdo = connectToDb();
    $userData = listUserById($pdo, $id);

    if(!$userData){
        header("Location: index.php");
        exit;
    }

    $nome = $userData['nome'];
    $email = $userData['email'];
} catch(PDOException $e){
    error_log("Erro no Editar (ID $id): " . $e->getMessage(), 3, __DIR__ . '/logs_do_sistema.txt');
    $erro = "Sistema temporariamente Indisponivel";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Bem vindo a página de edição</h1>

    <?php if(isset($erro)):?>
    <p><?= $erro ?></p>
    <?php endif?>


    <form id="editForm" data-id="<?= $id ?>">
        <fieldset>
            <legend>Alterar Dados Pessoais</legend>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" required placeholder="Seu nome" value="<?= $nome ?>">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required placeholder="seu email" value="<?= $email ?>">
            <button type="submit">Alterar</button>
        </fieldset>
    </form>

    <button onclick="javascript:location.replace('index.php')">Voltar</button>
    <script src="modal.js"></script>
    <script src="script.js"></script>
</body>

</html>