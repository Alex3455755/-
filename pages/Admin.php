<?php
session_start();
include "../scripts/search.php";
include_once "../connection/connection.php";

if($_SESSION['session_role'] == 'user'){
    header("Location: ../index.php");
}

// Обработка выгрузки в Excel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_orders'])) {
    $res = search('orders', $db);
    $orders = $res['list'];
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="orders_export.csv"');
    header('Cache-Control: max-age=0');
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF");
    fputcsv($output, [
        'ID заказа',
        'ID товара',
        'ID клиента',
        'Количество',
        'Цена (руб)'
    ], ';');
    foreach ($orders as $order) {
        fputcsv($output, [
            $order['id'] ?? '',
            $order['productsId'] ?? '',
            $order['clientId'] ?? '',
            $order['count'] ?? '',
            $order['price'] ?? ''
        ], ';');
    }
    fclose($output);
    exit;
}

// Остальной код остается без изменений
$res = search('products',$db);
$cat = $res['category'];
$listElems = $res['list'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clients'])){
    $res = search('clients',$db);
    $cat = $res['category'];
    $listElems = $res['list'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products'])){
    $res = search('products',$db);
    $cat = $res['category'];
    $listElems = $res['list'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orders'])){
    $res = search('orders',$db);
    $cat = $res['category'];
    $listElems = $res['list'];
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_POST['delete_table'])) {
    $table = $_POST['delete_table'];
    $id = (int)$_POST['delete_id'];
    $allowed_tables = ['products', 'clients', 'orders'];
    if (!in_array($table, $allowed_tables)) {
        die("Недопустимая таблица");
    }
    $query = "DELETE FROM $table WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        die("Ошибка при удалении: ".mysqli_error($db));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <title>Admin</title>
    <style>
        .export-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .export-btn:hover {
            background-color: #45a049;
        }
        .buttons-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .delete {
    background-color: #f44336;
        text-decoration: none;
  display: block;
  border: none;
  border-radius: 5px;
  color: white;
  padding: 12px 18px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  flex: 1;
  margin: 2px;
}

.delete:hover {
    background-color: #d32f2f;
}

form[method="post"][style*="inline"] {
    display: inline;
    margin-left: 10px;
}
    </style>
</head>
<body>
<?php
$low = true;
include "../elements/header.php";?>
        <main>
            <form action="#" method="post">
                <input name="products" type="submit" value="Товары" />
            </form>
            <form action="#" method="post">
                <input name="clients" type="submit" value="Клиенты" />
            </form>
            <form action="#" method="post">
                <input name="orders" type="submit" value="Заказы" />
            </form>
        </main>
        <div class="listElems">
            <?php

            function decryptAES($encryptedData, $key){

		$data = base64_decode($encryptedData);

		if($data === false || strlen($data)<17){
			error_log('Invalid data');
			return false;
		}

		$iv = substr($data,0,16);
		 
		$encrypted = substr($data,16);

		$keyHash = md5($key);
		$keyBytes = hex2bin($keyHash);

		$decrypted = openssl_decrypt(
			$encrypted,
			'aes-128-cbc',
			$keyBytes,
			OPENSSL_RAW_DATA,
			$iv
		);

		return $decrypted;
	}


	$secretKey = "qazalskdjflksjdfks";
            if($cat == 'products'){
                foreach($listElems as $elem)
                {
                    echo '<div class="product-card">
                    <div class="details">
                    <div class="info"><strong>ID:</strong>' . $elem['id'] . '</div>
                    <h3>' . $elem['name'] . '</h3>
                    <div class="price"><strong>Стоимость:</strong>' . $elem['price'] . ' руб.</div>
                    <div class="info"><strong>Количество:</strong> ' . $elem['count'] . '</div>
                    <div class="buttons">
                        <a href="EditProduct.php?id='.$elem['id'].'">Изменить</a>
                        <form method="post" action="" style="display:inline;">
        <input type="hidden" name="delete_id" value="'.$elem['id'].'">
        <input type="hidden" name="delete_table" value="products">
        <button type="submit" class="delete">Удалить</button>
      </form>
                    </div>
                  </div>
                  </div>';        
                }
                echo '<a href="addProduct.php" class="add-user-link"><span class="plus-icon">+</span></a>';
            }else if($cat == 'clients'){
                foreach($listElems as $elem)
                {
                   echo '<div class="user-card">
                   <div class="info"><strong>ID:</strong>' .$elem['id'] .'</div>
                <div class="name">'. decryptAES($elem['login'],$secretKey) . '</div>
                <div class="role"><strong>Роль:</strong> '. $elem['role'] . '</div>
                <div class="info"><strong>Телефон:</strong>'. decryptAES($elem['phone'],$secretKey) .'</div>
                <div class="buttons">
                    <a href="EditUser.php?id=' .$elem['id'].'" >Изменить</a>
                    <form method="post" action="" style="display:inline;">
        <input type="hidden" name="delete_id" value="'.$elem['id'].'">
        <input type="hidden" name="delete_table" value="clients">
        <button type="submit" class="delete">Удалить</button>
      </form>
                </div>
                </div>';
                }
            }else if($cat == 'orders'){
                // Кнопка экспорта для заказов
                echo '<div class="buttons-container">
                    <div></div>
                    <form action="#" method="post">
                        <button type="submit" name="export_orders" class="export-btn">Экспорт в Excel (CSV)</button>
                    </form>
                </div>';
                
                foreach($listElems as $elem)
                {
                   echo '<div class="data-card">
                    <div class="info"><strong>Номер:</strong>'. $elem['id'] . '</div>
                <div class="sequence"><strong>Товары:</strong>'. $elem['productsId'] . '</div>
                <div class="sum"><strong>Сумма:</strong>' . $elem['price'] . '</div>
                <div class="date"><strong>Id клиента:</strong>'. $elem['clientId'] . ' </div>
                <div class="buttons">
                    <a href="EditOrder.php?id=' .$elem['id'].'" >Изменить</a>
                    <form method="post" action="" style="display:inline;">
        <input type="hidden" name="delete_id" value="'.$elem['id'].'">
        <input type="hidden" name="delete_table" value="orders">
        <button type="submit" class="delete">Удалить</button>
      </form>
                </div>
                </div>';
                }
            }
            ?>
        </div>
        <script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('form[method="post"] button.delete');
    
    deleteForms.forEach(form => {
        form.addEventListener('click', function(e) {
            if (!confirm('Вы уверены, что хотите удалить этот элемент?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
</body>
</html>