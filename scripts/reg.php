<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['phone'])) {
        $role = "user";
        $phone = htmlspecialchars($_POST['phone']);
        $username = htmlspecialchars($_POST['login']);
        $password = htmlspecialchars($_POST['password']);
        $query = "SELECT * FROM Clients WHERE login = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message = "Имя пользователя уже существует! Пожалуйста, выберите другое.";
        } else {
            $insertQuery = "INSERT INTO Clients (login, password, phone, role) VALUES (?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($db, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "ssss", $username, $password, $phone, $role);

            if (mysqli_stmt_execute($insertStmt)) {
                header("Location: pages/autorization.php");
            } else {
                $message = "Не удалось создать аккаунт!";
            }

            mysqli_stmt_close($insertStmt);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($db);
    } else {
        $message = "Заполните все поля!";
    }
}
?>