<?php


session_start();


if(isset($low)){
    $level = $low;
}

?>




<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
    margin: 0;
    font-family: Arial, sans-serif;
}
body{
    --firstColor: rgb(46, 205, 80);
    --subColor: rgb(223, 68, 68);
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 60px;
    padding: 10px 20px;
    background-color: var(--firstColor);
}
.header a {
    color: white;
    text-decoration: none;
    margin: 0 15px;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}
.header a:hover {
    background-color: var(--subColor);
}
    </style>
</head>
<body>
    <div class="header">
        <?php
        if($level){
            if (isset($_SESSION['session_username'])){
                echo '
                <a href="../index.php" class="logo">СпортМания</a>
                <nav>
                <a href="basket.php">Корзина</a>
                <a href="profil.php?id=' . $_SESSION['id_user'] . '">Профиль</a>
            </nav>';
            }else{
                echo '
                <a href="../index.php" class="logo">СпортМания</a>
                <nav>
                <a href="autorization.php">Войти</a>
                <a href="pages/registration.php">Регистрация</a>
                <a href="basket.php">Корзина</a>
            </nav>';
            }
        }else{
            if (isset($_SESSION['session_username'])){
                echo '
                <a href="index.php" class="logo">СпортМания</a>
                <nav>
                <a href="pages/basket.php">Корзина</a>
                <a href="pages/profil.php?id=' . $_SESSION['id_user'] . '">Профиль</a>
            </nav>';
            }else{
                echo '
                <a href="index.php" class="logo">СпортМания</a>
                <nav>
                <a href="pages/autorization.php">Войти</a>
                <a href="pages/registration.php">Регистрация</a>
                <a href="pages/basket.php">Корзина</a>
            </nav>';
            }
        }
        ?>
    </div>