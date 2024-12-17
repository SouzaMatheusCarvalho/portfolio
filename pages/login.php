<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: admin.php');
    exit();
}

$config = include('C:\Users\carva\Desktop\config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $valid_user = $config['username'];
    $valid_password = $config['password'];

    if ($username === $valid_user && $password === $valid_password) {
        $_SESSION['user'] = $username;
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Credenciais incorretas';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../css/style-login.css">
</head>
<body>
    <form action="login.php" method="POST" class="formulario">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Entrar</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
