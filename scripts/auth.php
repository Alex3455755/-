<?php
require_once __DIR__ . '/../connection/connection.php';
session_start();
function decryptAES($encryptedBase64, $key = SECRET_KEY) {
    $keyHash = md5($key, true);
    
    $combined = base64_decode($encryptedBase64);
    if ($combined === false) {
        return false;
    }
    
    $iv_length = 16;
    $iv = substr($combined, 0, $iv_length);
    $ciphertext = substr($combined, $iv_length);
    
    $decrypted = openssl_decrypt(
        $ciphertext,
        'AES-128-CBC',
        $keyHash,
        OPENSSL_RAW_DATA,
        $iv
    );
    
    return $decrypted;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $query = mysqli_query($db, "SELECT * FROM Clients");
        $numrows = mysqli_num_rows($query);

        if($numrows != 0) {
            while($row = mysqli_fetch_assoc($query)){
                $decrypted_username = decryptAES($row['login'], SECRET_KEY);
                $decrypted_password = decryptAES($row['password'], SECRET_KEY);
                if($username == $decrypted_username && $password == $decrypted_password){
                    $dbid = $row['id'];
                    $dbusername = $row['login'];
                    $dbpassword = $row['password'];
                    $dbrole = $row['role'];
                    
                    $_SESSION['id_user'] = $dbid;
                    $_SESSION['session_username'] = $username;
                    $_SESSION['session_role'] = $dbrole;
                    
                    if($dbrole == 'user'){
                        header("Location: ../index.php");
                    } else {
                        header("Location: ../pages/Admin.php");
                    }
                    exit();
                }
            }
            $message = "Неверный логин или пароль!";
        } else {
            $message = "Неверный логин или пароль!";
        }
    } else {
        $message = "Все поля обязательны к заполнению!";
    }
}
?>
