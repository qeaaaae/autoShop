<?php
require "pdo_connection.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: errorPage.php?msg=Вы не авторизованы!");
}
if (isset($_GET['qty']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $qty = $_GET['qty'];
    $res = $conn->query("SELECT cards.qty, cart.cart_qty FROM cards INNER JOIN cart on cards.id = cart.product_id WHERE cards.id like $id")->fetch();
    if ($res["qty"] >= $qty) {
        if ($qty > 0) {
            $update = $conn->exec("UPDATE cart SET cart_qty = $qty WHERE product_id = $id");
        } else {
            $delete = $conn->exec("DELETE FROM cart WHERE product_id = $id");
        }
        header("Location: cart.php");
    } else {
        header("Location: errorPage.php?msg=Данного товара нет больше в наличие");
    }
} else if (isset($_GET['id'])) {
    $userId = $_SESSION['user']['id'];
    $id = $_GET['id'];
    $one = $conn->query("SELECT * FROM cards WHERE id like $id")->fetch();
    $res = $conn->query("SELECT qty FROM cards WHERE id like $id")->fetch();
    if ($res["qty"] != 0) {
        $pId = $one["id"];
        $res = $conn->query("SELECT count(*) FROM cart WHERE product_id like $pId and user_id like $userId")->fetch();
        if ($res["count(*)"] > 0) {
            $res = $conn->query("SELECT cards.qty, cart.cart_qty FROM cards INNER JOIN cart on cards.id = cart.product_id WHERE cards.id like $id and cart.user_id like $userId")->fetch();
            if ($res["qty"] > $res["cart_qty"]) {
                $update = $conn->exec("UPDATE cart SET cart_qty = cart_qty + 1 WHERE product_id = $id");
            }
        } else {
            $result = $conn->exec("INSERT INTO cart (user_id, product_id, cart_qty) VALUES ('$userId', '$pId', 1)");
        }
        header("Location: cart.php");
    } else {
        header("Location: errorPage.php?msg=Товара нет в наличие");
    }
}
