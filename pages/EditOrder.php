<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование заказа</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            box-sizing: border-box;
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
        form{
            padding-right: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
            color: #333;
        }
        .form-container input, .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
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
        .form-container button:hover {
            background-color: rgb(223, 68, 68);
        }
        .buttons {
            margin-top: 20px;
        }
        .buttons a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 5px;
        }
        .buttons .edit {
            background-color: rgb(46, 205, 80);
        }
        .buttons .delete {
            background-color: rgb(223, 68, 68);
            margin-top: 40px;
        }
    </style>
</head>
<body>
<?php
    include_once "../connection/connection.php";
    $post_id = $_GET['id'] ?? null;

    if ($post_id) {
        // Запрос для получения данных записи
        $query = mysqli_query($db, "SELECT orders.*, Clients.login, Clients.id as client_id FROM orders JOIN Clients ON orders.clientId = Clients.id WHERE orders.id = $post_id;");
        $response = mysqli_num_rows($query);

        while ($row = mysqli_fetch_assoc($query)) {
            $id = $row['id'];
            $loginClient = $row['login'];
            $clientId = $row['client_id'];
            $productsId = $row['productsId'];
            $count = $row['count'];
            $price = $row['price'];
        }
    } else {
        echo "Неверный ID записи.";
        exit();
    }

    // Получаем список всех клиентов из базы данных
    $clients_query = mysqli_query($db, "SELECT id, login FROM Clients");
    $clients = [];
    while ($client = mysqli_fetch_assoc($clients_query)) {
        $clients[] = $client;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProduct'])){
        $id = $post_id;
        $items = $_POST['items'];
        $price = $_POST['price'];
        $clientId = $_POST['client'];
        $query = mysqli_query($db, "UPDATE `orders` SET `productsId`='$items', `clientId`='$clientId', `count`='1', `price`='$price' WHERE id = '$id'");

        header("Location: Admin.php");
    }
?>
    <div class="form-container">
        <h2>Редактировать заказ</h2>
        <form action="#" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <label for="items">Товары:</label>
            <input type="text" id="items" name="items" value="<?php echo htmlspecialchars($productsId); ?>" required = "true">

            <label for="price">Сумма:</label>
            <input type="num" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required = "true">

            <label for="client">Клиент:</label>
            <select id="client" name="client" required>
                <?php foreach ($clients as $client): ?>
                    <option value="<?php echo $client['id']; ?>" <?php echo $client['id'] == $clientId ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($client['login']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="buttons">
                <button type="submit" name="updateProduct" class="edit">Сохранить</button>
                <button href="#" class="delete">Удалить</button>
            </div>
        </form>
    </div>
</body>
</html>