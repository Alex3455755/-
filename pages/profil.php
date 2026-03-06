<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/profile.css">
    <title>Профиль</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            box-sizing: border-box;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .form-container h2 {
            color: rgb(46, 205, 80);
            margin-bottom: 20px;
        }
        form {
            padding-right: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
            color: #333;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container input[type='submit'] {
            width: 100%;
            padding: 10px;
            background-color: rgb(46, 205, 80);
            margin-right: -20px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-container input[type='submit']:hover {
            background-color: rgb(223, 68, 68);
        }
        .form-container .logout-btn {
            width: 100%;
            padding: 10px;
            background-color: #dc3545;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .form-container .logout-btn:hover {
            background-color: #c82333;
        }
        .formLogout{
            display: flex;
            justify-content: center;
            align-items: center;
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>
</head>
<body>
<?php



$low = true;
include "../elements/header.php";

include_once "../connection/connection.php";

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
session_start();
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$post_id = $_GET['id'] ?? null;

if ($post_id) {
    $query = mysqli_query($db, "SELECT * FROM `Clients` WHERE `id` = $post_id");
    $response = mysqli_num_rows($query);

    while ($row = mysqli_fetch_assoc($query)) {
            $id = $row['id'];
            $name = decryptAES($row['login'],SECRET_KEY);
            $password = decryptAES($row['password'],SECRET_KEY);
            $role = $row['role'];
            $phone = decryptAES($row['phone'],SECRET_KEY);
        }
} else {
    echo "Неверный ID записи.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
   $id = $_POST['id'];
        $name= encryptAES($_POST['name'],SECRET_KEY);
        $password= encryptAES($_POST['password'],SECRET_KEY);
        $role= $_POST['role'];
        $phone= encryptAES($_POST['phone'],SECRET_KEY);
    $query = mysqli_query($db, "UPDATE `Clients` SET `login`='$name',`password`='$password',`role`='$role',`phone`='$phone' WHERE `id`='$id'");

    header("Location: ../index.php");
    exit();
}
?>
<div class="container">
    <div class="form-container">
        <h2>Изменить данные пользователя</h2>
        <form action="#" method="post">
            <input type="hidden" name='id' value="<?php echo htmlspecialchars($id); ?>" />
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            <label for="password">Пароль</label>
            <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
            <input type="hidden" id="role" name="role" value="<?php echo htmlspecialchars($role); ?>" required>
            <label for="phone">Номер телефона:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            <input type="submit" value="Изменить" name='updateUser' />
        </form>
        <form class="formLogout" action="#" method="post">
            <button type="submit" name="logout" class="logout-btn">Выйти из аккаунта</button>
        </form>
    </div>
</div>
</body>
</html>