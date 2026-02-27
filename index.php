<?php
require_once 'data/list_users.php';
require_once 'config/connection.php';


try{
    $pdo = connectToDb();
    $UsersList = listAllUsers($pdo);
} catch (PDOException $e) {
    $UsersList = [];
    $erroConexao = "OPS! Estamos com uma instabilidade";

    error_log("Erro Crítico: " . $e->getMessage(), 3, __DIR__ . '/logs_do_sistema.txt');
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de usuarios</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Lista de Usuários do banco de dados</h1>

    <?php if(isset($erroConexao)):?>
    <p><?= $erroConexao ?></p>
    <?php endif?>

    <?php if(empty($UsersList) && !isset($erroConexao)):?>
    <p id="aviso">Lista vazia 😅, fique a vontade para inserir um registro</p>


    <?php endif?>

    <?php if(!empty($UsersList)):?>
    <ul id="lista-usuarios">
        <?php foreach($UsersList as $user):?>
        <li>
            <div id="text"><strong>Nome:</strong> <?= $user['nome'] ?></div>

            <div id="actions">
                <button data-name="<?= $user['nome'] ?>" data-id="<?= $user['id'] ?>" title="Apagar Registro">
                    🗑️
                </button>
                <a title="Editar Registro" href="editar.php?id=<?= $user['id'] ?>">✏️</a>
            </div>

        </li>
        <?php endforeach;?>
    </ul>
    <?php endif?>



    <a href="cadastrar.php">Inserir Novo Registro</a>
</body>
<script src="modal.js"></script>
<script src="script.js"></script>

</html>