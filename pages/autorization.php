<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/autorization.css">
    <title>Вход</title>
</head>
<body>
<?php
require_once "../connection/connection.php";
include "../scripts/auth.php";
 if (!empty($message)) {
    echo '<div class="error">' . $message . '<span onclick="ignore()" style="cursor: pointer;">x</span></div>';
    echo '<script>';
    echo 'function ignore() { document.querySelector(\'.error\').style.display = \'none\'; }';
    echo '</script>';
}
 ?>
    <div class="mainWindow">
        <div class="auth-form">
            <h2>Авторизация</h2>
            <form action="#" method="post" name="login">
                <input type="text" name="username" placeholder="Логин" required = "true">
                <input type="password" name="password" placeholder="Пароль" required = "true">
                <input name="login" id="login" type="submit" value="Войти">
            </form>
        </div>
    </div>
</body>
</html>