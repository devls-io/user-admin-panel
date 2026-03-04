<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <h1>Bem vindo ao Sistema</h1>
    <p>Por favor preencha os campos abaixo</p>

    <form id="loginForm">
        <fieldset>
            <legend>Dados do Admin</legend>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="exemplo@email.com" required>
            <label for="senha">Senha</label>

            <div class="password-container">
                <input type="password" name="senha" id="senha" placeholder="senha" required>
                <button title="mostrar senha" type="button" id="togglePassword">
                    👁️
                </button>

            </div>


            <button type="submit">Logar</button>
        </fieldset>
    </form>

    <script src="assets/js/modal.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="./assets/js/auth.js"></script>
</body>

</html>