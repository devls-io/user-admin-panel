<?php require_once 'helpers/auth_check.php';
include 'helpers/theme.php';

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?= $classeBody ?>">

    <h1>Cadastro de novos registros</h1>


    <form id="insertForm">
        <fieldset>
            <legend>Dados Pessoais</legend>
            <div class="avatar-upload-container">
                <label for="avatar">Foto de Perfil</label>
                <div class="avatar-preview">
                    <img src="assets/uploads/avatars/default-avatar.png" alt="Preview do Avatar">
                </div>
                <input type="file" name="avatar" id="avatar" accept="image/*">
            </div>

            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" placeholder="Seu nome" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="exemplo@email.com">
            <button type="submit">Cadastrar</button>
        </fieldset>
    </form>

    <button onclick="javascript:location.replace('index.php')">Voltar</button>
    <script src="assets/js/modal.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>