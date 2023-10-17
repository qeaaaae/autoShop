<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mailer/src/PHPMailer.php';
require 'mailer/src/Exception.php';
require 'mailer/src/SMTP.php';
require "pdo_connection.php";
require "functions.php";
require "config.php";
$mail = new PHPMailer(true);
session_start();


if (!isset($_SESSION["user"])) {
    header("Location: errorPage.php?msg=Вы не авторизованы!");
}
if (!isset($_GET['tp'])) {
    header("Location: errorPage.php?msg=Ошибка!");
} else {
    $totalPrice = $_GET['tp'];
    $userId = $_SESSION['user']['id'];
    $cypher = create_cypher();
    $res = $conn->query("SELECT * FROM cart where user_id like $userId");
    $conn->exec("INSERT INTO orders (user_id, cypher, price) VALUES ('$userId', '$cypher', '$totalPrice')");
    foreach ($res as $one) {
        $product_id = $one['product_id'];
        $cart_qty = $one['cart_qty'];
        $upd = $conn->exec("UPDATE cards SET qty = qty - $cart_qty WHERE id = $product_id");
        $ord = $conn->query("SELECT id FROM orders WHERE cypher = '$cypher'")->fetch();
        $ord_id = $ord['id'];
        $ins = $conn->exec("INSERT INTO order_item (order_id, product_id, qty) VALUES ('$ord_id', '$product_id', '$cart_qty')");
    }
    $del = $conn->exec("DELETE FROM cart  WHERE user_id like $userId");
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $LOGIN;
        $mail->Password = $PASSWORD;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom($LOGIN, 'АвтоМагазин');
        $mail->addAddress($_SESSION['user']['email'], $_SESSION['user']['username']);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Заказ ' . $cypher . ' оформлен!';
        $mail->Body = '<h1>Спасибо за покупку!</h1></br><p>Заказ <b>' . $cypher . ' успешно оформлен!</b></br>Итого: <b>' . number_format($totalPrice) . '</b> руб.</p></br> <a href="http://cards/profile.php">Ознакомиться в личном кабинете</a>';
        $mail->AddEmbeddedImage('images/thx.jpg', 'Спасибо!');
        $mail->AltBody = 'Спасибо за покупку!';
        $mail->send();
    } catch (Exception $e) {
        echo "Произошла ошибка: {$mail->ErrorInfo}";
    }
    header("Location: errorPage.php?msg=Заказ оформлен успешно");
}
