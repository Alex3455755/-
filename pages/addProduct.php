<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление нового товара</title>
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addProduct'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $query = mysqli_query($db, "INSERT INTO `products`( `name`, `price`, `count`) VALUES ('$name','$price','$quantity')");

        header("Location: Admin.php");
    }


    ?>
    <div class="form-container">
        <h2>Добавить новый товар</h2>
        <form action="#" method="post">
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Цена:</label>
            <input type="text" id="price" name="price" required>

            <label for="quantity">Количество:</label>
            <input type="text" id="quantity" name="quantity" required>
            <input type="submit" value='Добавить' name="addProduct" />
        </form>
    </div>
</body>
</html>
