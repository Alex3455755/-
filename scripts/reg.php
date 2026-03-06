<?php
require_once __DIR__ . '/../connection/connection.php';

// Функция ДО использования
function encryptAES($data, $key = SECRET_KEY) {
    $keyHash = md5($key, true);
    $iv = random_bytes(16);
    
    $encrypted = openssl_encrypt(
        $data, 
        'AES-128-CBC',
        $keyHash, 
        OPENSSL_RAW_DATA,
        $iv
    );
    $combined = $iv . $encrypted;
    
    return base64_encode($combined);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['phone'])) {
        $role = "user";
        $phone_raw = trim($_POST['phone']);
        $username_raw = trim($_POST['login']);
        $password_raw = trim($_POST['password']);
        
        // Проверяем существующего пользователя
        $checkQuery = "SELECT id FROM Clients WHERE login = ?";
        $checkStmt = mysqli_prepare($db, $checkQuery);
        $username_check = encryptAES($username_raw, SECRET_KEY);
        mysqli_stmt_bind_param($checkStmt, "s", $username_check);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);
        
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $message = "Пользователь с таким логином уже существует!";
            mysqli_stmt_close($checkStmt);
        } else {
            mysqli_stmt_close($checkStmt);
            
            $phone = encryptAES($phone_raw, SECRET_KEY);
            $username = encryptAES($username_raw, SECRET_KEY);
            $password = encryptAES($password_raw, SECRET_KEY);
            
            $insertQuery = "INSERT INTO Clients (login, password, phone, role) VALUES (?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($db, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "ssss", $username, $password, $phone, $role);
            
            if (mysqli_stmt_execute($insertStmt)) {
                mysqli_stmt_close($insertStmt);
                mysqli_close($db);
                header("Location: ../pages/autorization.php");
                exit();
            } else {
                $message = "Не удалось создать аккаунт!";
            }
            mysqli_stmt_close($insertStmt);
        }
    } else {
        $message = "Заполните все поля!";
    }
    mysqli_close($db);
}
?>
