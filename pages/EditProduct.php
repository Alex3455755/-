<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменение товара</title>
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
    </style>
</head>
<body>
    <?php
    include_once "../connection/connection.php";
    $post_id = $_GET['id'] ?? null;

    if ($post_id) {
        // Запрос для получения данных записи
        $query = mysqli_query($db, "SELECT * FROM `products` WHERE `id` = $post_id");
        $response = mysqli_num_rows($query);

        while ($row = mysqli_fetch_assoc($query)) {
            $id = $row['id'];
            $name = $row['name'];
            $price = $row['price'];
            $count = $row['count'];
        }
    } else {
        echo "Неверный ID записи.";
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProduct'])){
        $id = $_POST['id'];
        $name= $_POST['name'];
        $price= $_POST['price'];
        $count= $_POST['quantity'];
        $query = mysqli_query($db, "UPDATE `products` SET `name`='$name',`price`='$price',`count`='$count' WHERE `id`='$id'");

        header("Location: Admin.php");
    }


    ?>
    <div class="form-container">
        <h2>Изменить товар</h2>
        <form action="#" method="post">
            <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>" required>

            <label for="name">Название:</label>
            <input type="text" id="name" name="name"value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="price">Цена:</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required>

            <label for="quantity">Количество:</label>
            <input type="text" id="quantity" value="<?php echo htmlspecialchars($count); ?>" name="quantity" required>
            <input type="submit"  value="Изменить" name='updateProduct' />
        </form>
    </div>
</body>
</html>
