

 <!DOCTYPE html>
 <html lang="ru">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="../styles/header.css">
     <link rel="stylesheet" href="../styles/autorization.css">
     <title>Регистрация</title>
 </head>
 <body>
 <?php
require_once "../connection/connection.php";
include "../scripts/reg.php";

if (!empty($message)) {
    echo '<div class="error">' . $message . '<span onclick="ignore()" style="cursor: pointer;">x</span></div>';
    echo '<script>';
    echo 'function ignore() { document.querySelector(\'.error\').style.display = \'none\'; }';
    echo '</script>';
}
?>
     <div class="mainWindow">
         <div class="auth-form">
             <h2>Регистрация</h2>
             <form action="registration.php" id="registerform" method="post" name="registerform">
                 <input type="text" placeholder="Логин" name="login" required = "true">
                 <input type="password" placeholder="Пароль" name="password" required = "true">
                 <input type="password" placeholder="Подтвердите пароль" required = "true">
                 <input type="tel" placeholder="Номер телефона" name="phone" required = "true">
                 <input id="register" name="register" type="submit" value="Зарегистрироваться">
                 <a href="autorization.php">у меня уже есть аккаунт</a>
             </form>
         </div>
     </div>
 </body>
 </html>