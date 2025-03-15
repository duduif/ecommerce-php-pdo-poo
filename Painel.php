<?php
require_once 'classes/Login.php';

$mensagem = "";

if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = $_POST['pswd'];

    $login = new Login($usuario, $email, $senha);
    $mensagem = $login->autenticar();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DuduStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <!-- form de login -->
        <div class="login">
            <form action="" method="post">
                <label for="chk" aria-hidden="true">DuduStore</label>
                <input type="text" name="usuario" placeholder="Usuário" required>
                <input type="email" name="email" placeholder="Email" required>          
                <input type="password" name="pswd" placeholder="Senha" required>
                <button type="submit" name="login">Login</button>
                
                <div class="tema-selector">
                    <label for="tema">Escolha o Tema:</label>
                    <select name="tema" id="tema">
                        <option value="claro">Tema Claro</option>
                        <option value="escuro">Tema Escuro</option>
                    </select>
                </div>

                <!-- mostra mensagem de erro se houver -->
                <?php if (!empty($mensagem)) : ?>
                    <p style="color: red;"><?= $mensagem; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
