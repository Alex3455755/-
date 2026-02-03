<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/cart.css">
    <title>Корзина</title>
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
        }
        .cart-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px 0;
            padding: 10px;
            display: flex;
            align-items: center;
        }
        .cart-item img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
            margin-right: 20px;
        }
        .cart-item h3 {
            margin: 0;
            font-size: 18px;
        }
        .cart-item p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .cart-item .price {
            font-size: 18px;
            color: #e74c3c;
        }
        .cart-item .quantity {
            font-size: 16px;
            color: #2ecc71;
        }
        .cart-item .remove {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .cart-item .remove:hover {
            background-color: #c0392b;
        }
        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 20px;
            color: #333;
        }
        .checkout {
            display: block;
            text-align: center;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .checkout:hover {
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
    </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$low = true;
include "../elements/header.php";

if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: basket.php");
    exit();
}
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
                }
            }
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: basket.php");
    exit();
}

if (isset($_POST['checkout'])) {
    include_once "../connection/connection.php";
    
    $client_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
    $product_ids = [];
    $total_quantity = 0;
    $total_price = 0;
    $all_available = true;
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        
        $product_query = mysqli_query($db, "SELECT price, count FROM products WHERE id = $product_id");
        $product_data = mysqli_fetch_assoc($product_query);
        
        if ($product_data['count'] < $quantity) {
            $all_available = false;
            break;
        }
        
        $product_ids[] = $product_id;
        $total_quantity += $quantity;
        $total_price += $product_data['price'] * $quantity;
    }
    if ($all_available && !empty($product_ids)) {
        $products_id_list = implode(',', $product_ids);
        $insert_query = "INSERT INTO orders (productsid, clientid, count, price) 
                        VALUES ('$products_id_list', $client_id, $total_quantity, $total_price)";
        mysqli_query($db, $insert_query);
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            
            $update_query = "UPDATE products SET count = count - $quantity WHERE id = $product_id";
            mysqli_query($db, $update_query);
        }
        
        $_SESSION['cart'] = [];
        header("Location: basket.php?success=1");
        exit();
    } else {
        header("Location: basket.php?error=not_available");
        exit();
    }
}
?>

<div class="container">
    <h2>Корзина</h2>
    <?php
    include_once "../connection/connection.php";
    
    if (isset($_GET['success'])) {
        echo '<div class="success-message">Заказ успешно оформлен!</div>';
    }
    
    if (empty($_SESSION['cart'])) {
        echo '<p>Корзина пуста</p>';
    } else {
        $total = 0;
        
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            
            $product_query = mysqli_query($db, "SELECT id, name, price FROM products WHERE id = $product_id");
            $product = mysqli_fetch_assoc($product_query);
            
            if ($product) {
                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;
                
                echo '<div class="cart-item">';
                echo '<img src="path_to_image/' . $product["id"] . '.jpg" alt="' . $product["name"] . '">';
                echo '<div>';
                echo '<h3>' . $product["name"] . '</h3>';
                echo '<p class="price">Цена: ' . $product["price"] . ' руб.</p>';
                
                echo '<div class="quantity-controls">';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="product_id" value="' . $product["id"] . '">';
                echo '<input type="hidden" name="action" value="decrease">';
                echo '<button type="submit" name="update_quantity" class="quantity-btn">-</button>';
                echo '</form>';
                
                echo '<span style="margin: 0 10px;">' . $quantity . '</span>';
                
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="product_id" value="' . $product["id"] . '">';
                echo '<input type="hidden" name="action" value="increase">';
                echo '<button type="submit" name="update_quantity" class="quantity-btn">+</button>';
                echo '</form>';
                echo '</div>';
                
                echo '</div>';
                
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="product_id" value="' . $product["id"] . '">';
                echo '<button type="submit" name="remove_item" class="remove">Удалить</button>';
                echo '</form>';
                
                echo '</div>';
            }
        }
        
        echo '<div class="total">';
        echo '<p>Итого: ' . $total . ' руб.</p>';
        echo '<form method="post" action="">';
        echo '<button type="submit" name="checkout" class="checkout">Оформить заказ</button>';
        echo '</form>';
        echo '</div>';
    }
    ?>
</div>

<style>
    /* Стили остаются без изменений */
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
    }
    
    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .cart-item {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 10px 0;
        padding: 10px;
        display: flex;
        align-items: center;
    }
    
    .cart-item img {
        max-width: 100px;
        height: auto;
        border-radius: 5px;
        margin-right: 20px;
    }
    
    .cart-item h3 {
        margin: 0;
        font-size: 18px;
    }
    
    .cart-item p {
        margin: 5px 0;
        font-size: 16px;
        color: #555;
    }
    
    .cart-item .price {
        font-size: 18px;
        color: #e74c3c;
    }
    
    .cart-item form {
        margin-left: auto;
    }
    
    .cart-item .remove {
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
    }
    
    .cart-item .remove:hover {
        background-color: #c0392b;
    }
    
    .total {
        text-align: right;
        margin-top: 20px;
        font-size: 20px;
        color: #333;
    }
    
    .checkout {
        display: block;
        text-align: center;
        margin: 20px auto;
        padding: 10px 20px;
        background-color: #3498db;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }
    
    .checkout:hover {
        background-color: #2980b9;
    }
</style>