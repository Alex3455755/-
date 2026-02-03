<?php


function search($category, $base) {
    if ($category == 'clients') {
        $query = mysqli_query($base, "SELECT * FROM Clients");
        $response = mysqli_num_rows($query);
        $list = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $elemList = [];
            $elemList['id'] = $row['id'];
            $elemList['login'] = $row['login'];
            $elemList['phone'] = $row['phone'];
            $elemList['role'] = $row['role'];
            $list[] = $elemList;
        }

        return ["category" => $category, "list" => $list];
    }else if($category == 'products'){
        $query = mysqli_query($base, "SELECT * FROM products");
        $response = mysqli_num_rows($query);
        $list = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $elemList = [];
            $elemList['id'] = $row['id'];
            $elemList['name'] = $row['name'];
            $elemList['price'] = $row['price'];
            $elemList['count'] = $row['count'];
            $list[] = $elemList;
        }

        return ["category" => $category, "list" => $list];
    }else if($category == 'orders'){
        $query = mysqli_query($base, "SELECT * FROM orders");
        $response = mysqli_num_rows($query);
        $list = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $elemList = [];
            $elemList['id'] = $row['id'];
            $elemList['productsId'] = $row['productsId'];
            $elemList['clientId'] = $row['clientId'];
            $elemList['count'] = $row['count'];
            $elemList['price'] = $row['price'];
            $list[] = $elemList;
        }

        return ["category" => $category, "list" => $list];
    }
}






?>