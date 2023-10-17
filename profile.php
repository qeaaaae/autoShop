<?php
require "pdo_connection.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: errorPage.php?msg=Вы не авторизованы!");
}
$userId = $_SESSION['user']['id'];
$status_id = $_SESSION['user']['status_id'];
$status = $conn->query("SELECT * FROM status WHERE id = $status_id")->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <title>Профиль</title>
</head>

<body>
    <?php require "UI/navbar.php"; ?>
    <div class="container d-flex justify-content-center align-items-start">
        <div class="d-flex align-items-center shadow w-50 bg-white rounded px-3 mb-5">
            <div class="container d-flex flex-column gap-3">
                <div class="d-flex gap-3 flex-column">
                    <div class="d-flex flex-column w-100 gap-2">
                        <h3>Личные данные</h3>
                        <div>
                            <label for="formGroupExampleInput" class="form-label">Имя</label>
                            <input type="text" class="form-control" id="formGroupExampleInput" value="<?php echo $_SESSION["user"]["username"] ?>" disabled>
                        </div>
                        <div>
                            <label for="formGroupExampleInput2" class="form-label">Фамилия</label>
                            <input type="text" class="form-control" id="formGroupExampleInput2" value="<?php echo $_SESSION["user"]["surname"] ?>" disabled>
                        </div>
                        <div>
                            <label for="formGroupExampleInput2" class="form-label">Электронная почта</label>
                            <input type="text" class="form-control" id="formGroupExampleInput2" value="<?php echo $_SESSION["user"]["email"] ?>" disabled>
                        </div>
                        <div>
                            <label for="formGroupExampleInput2" class="form-label">Номер телефона</label>
                            <input type="text" class="form-control" id="formGroupExampleInput2" value="<?php echo $_SESSION["user"]["phone"] ?>" disabled>
                        </div>
                        <div>
                            <label for="formGroupExampleInput2" class="form-label">Статус</label>
                            <input type="text" class="form-control" id="formGroupExampleInput2" value="<?php echo $status['name'] ?>" disabled>
                        </div>
                    </div>
                    <div class="d-flex gap-3 flex-column">
                        <h3>Мои заказы</h3>
                        <?php
                        $totalPrice = 0;
                        $amountProducts = 0;
                        $amountPositions = 0;
                        $re = $conn->query("SELECT * FROM orders WHERE user_id like $userId");
                        foreach ($re as $row) {
                            $cypher = $row['cypher'];
                            echo '
                                    <div class="order__item rounded d-flex flex-column gap-3 shadow-sm">
                                        <div><h4>Заказ: ' . $cypher . '</h4></div>
                                            <div class="d-flex gap-3 flex-column">
                                                <div class="d-flex gap-3 flex-column">
                                ';
                            $res = $conn->query("SELECT orders.cypher, orders.created_at as time, orders.price as total_price, order_item.qty, cards.name, cards.price, cards.image FROM orders INNER JOIN order_item on orders.id = order_item.order_id INNER JOIN cards on order_item.product_id = cards.id WHERE orders.user_id like $userId and orders.cypher like '$cypher' ORDER BY orders.id DESC");
                            foreach ($res as $one) {
                                echo '
                                                <div class="d-flex gap-3">
                                                    <img src="images/' . $one["image"] . '" alt="" class="w-25 custom__img border rounded">
                                                    <div class="d-flex flex-column justify-content-between">
                                                        <p><b>Название</b>: ' . $one["name"] . '</p>
                                                        <p><b>Цена</b>: ' . number_format($one["price"]) . ' руб.</p>
                                                        <p><b>Количество</b>: ' . $one["qty"] . '</p>
                                                    </div>
                                                </div>
                                    ';
                            }
                            echo '
                                                </div>
                                                <div class="d-flex flex-column align-self-end">
                                                    <div><b>Дата оформления</b>:</div>
                                                    <p>' . $one["time"] . '</p>
                                                    <div><b>Итого</b>:</div>
                                                    <h4>' . number_format($one["total_price"]) . ' руб.</h4>
                                                </div>
                                            </div>
                                    </div>
                                ';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require "UI/footer.php"; ?>
</body>

</html>