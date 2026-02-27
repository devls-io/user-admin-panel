<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Cadastro de novos registros</h1>

    <form id="insertForm">
        <fieldset>
            <legend>Dados Pessoais</legend>
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" placeholder="Seu nome" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="exemplo@email.com">
            <button type="submit">Cadastrar</button>
        </fieldset>
    </form>

    <button onclick="javascript:location.replace('index.php')">Voltar</button>
    <script src="modal.js"></script>
    <script src="script.js"></script>
</body>

</html>