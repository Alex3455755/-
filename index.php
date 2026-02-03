<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/main.css">
    <title>Главная</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            padding: 10px;
            width: 200px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .card h3 {
            margin: 10px 0;
            font-size: 18px;
        }
        .card p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .card .price {
            font-size: 18px;
            color: #e74c3c;
        }
        .card .count {
            font-size: 16px;
            color: #2ecc71;
        }
        .card .add-to-cart {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .card .add-to-cart:hover {
            background-color: #218838;
        }
        .cart-link {
            display: block;
            text-align: center;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .cart-link:hover {
            background-color: #2980b9;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .cart-item form {
            margin-left: auto;
        }
        .cart-item input[type="number"] {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .cart-item button[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .cart-item button[type="submit"]:hover {
            background-color: #2980b9;
        }
        .baner {
            height: 60vh;
            background: url("img/fon.jpg");
            background-size: cover;
            background-position: 50%;
            color: #2ecc71;
            display: flex;
            align-items: end;
            justify-content: center;
            font-size: 60px;
            padding-bottom: 70px;
            box-sizing: border-box;
        }
        .quantity-btn {
            background-color: #3498db;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
        }
        .quantity-btn:hover {
            background-color: #2980b9;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
<?php
// Start session and handle cart logic at the top
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Обработка добавления в корзину
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Проверяем, есть ли уже товар в корзине
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'quantity' => $quantity
        ];
    }
    
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Обработка изменения количества на главной странице
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];
    
    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            if ($action == 'increase') {
                $item['quantity']++;
            } elseif ($action == 'decrease') {
                $item['quantity']--;
                if ($item['quantity'] <= 0) {
                    unset($_SESSION['cart'][$key]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                }
            }
            break;
        }
    }
    
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Include header and other content after PHP logic
$low = false;
include "elements/header.php";
?>

<div class="baner">
    <p>Лучшее место для спорта!</p>
</div>
<div class="container">
    <?php
    include_once "connection/connection.php";

    $query = mysqli_query($db, "SELECT id, name, price, count FROM products");
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $inCart = false;
            $cartQuantity = 0;
            foreach ($_SESSION['cart'] as $item) {
                if ($item['product_id'] == $row['id']) {
                    $inCart = true;
                    $cartQuantity = $item['quantity'];
                    break;
                }
            }
            
            echo '<div class="card">';
            echo '<img src="path_to_image/' . $row["id"] . '.jpg" alt="' . $row["name"] . '">';
            echo '<h3>' . $row["name"] . '</h3>';
            echo '<p class="price">Цена: ' . $row["price"] . ' руб.</p>';
            echo '<p class="count">Количество: ' . $row["count"] . '</p>';
            
            if ($inCart && $cartQuantity > 0) {
                echo '<div class="quantity-controls" style="margin: 10px 0;">';
                echo '<form method="post" action="" style="display: inline-block;">';
                echo '<input type="hidden" name="product_id" value="' . $row["id"] . '">';
                echo '<input type="hidden" name="action" value="decrease">';
                echo '<button type="submit" name="update_quantity" class="quantity-btn">-</button>';
                echo '</form>';
                
                echo '<span style="margin: 0 10px;">' . $cartQuantity . '</span>';
                
                echo '<form method="post" action="" style="display: inline-block;">';
                echo '<input type="hidden" name="product_id" value="' . $row["id"] . '">';
                echo '<input type="hidden" name="action" value="increase">';
                echo '<button type="submit" name="update_quantity" class="quantity-btn">+</button>';
                echo '</form>';
                echo '</div>';
            } else {
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="product_id" value="' . $row["id"] . '">';
                echo '<button type="submit" name="add_to_cart" class="add-to-cart">В корзину</button>';
                echo '</form>';
            }
            
            echo '</div>';
        }
    } else {
        echo '<p>Нет данных</p>';
    }
    ?>
</div>
</body>
</html>