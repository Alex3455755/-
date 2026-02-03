<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $query = mysqli_query($db,"SELECT * FROM Clients WHERE login ='".$username."'  AND password = '". $password."'");
        $numrows = mysqli_num_rows($query);

        if($numrows != 0) {
           while($row=mysqli_fetch_assoc($query)){
            $dbid = $row['id'];
            $dbusername=$row['login'];
            $dbpassword=$row['password'];
            $dbrole = $row['role'];
           }
           if($username == $dbusername && $password == $dbpassword){
            $_SESSION['id_user'] = $dbid;
            $_SESSION['session_username'] = $username;
            $_SESSION['session_role'] = $dbrole;
            if($dbrole == 'user'){
                header("Location: ../index.php");
            }else{
                header("Location: ../pages/Admin.php");
            }

           }
        } else {
            $message = "Неверный логин или пароль!";
        }
    } else {
        $message = "Все поля обязательны к заполнению!";
    }
}
?>