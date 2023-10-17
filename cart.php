<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Магазин</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<link href="main.css" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php require "UI/navbar.php"; ?>
	<div class="container features">
		<div class="row g-5">
			<?php
			require "pdo_connection.php";
			session_start();
			if (!isset($_SESSION["user"])) {
				header("Location: errorPage.php?msg=Вы не авторизованы!");
			}
			$totalPrice = 0;
			$amountProducts = 0;
			$amountPositions = 0;
			$userId = $_SESSION["user"]["id"];
			$one = $conn->query("SELECT cards.id, cards.name, cards.description, cards.image, cards.category_id, cards.price, cards.qty, category.title, cart.cart_qty FROM cards INNER JOIN category on cards.category_id = category.id INNER JOIN cart on cards.id = cart.product_id WHERE cart.user_id like $userId");
			foreach ($one as $row) {
				$totalPrice += ($row["price"] * $row["cart_qty"]);
				$amountPositions += $row["cart_qty"];
				$amountProducts += 1;
			}
			echo '
				<div class="col-3 custom__category">
					<h3>Корзина</h3>
					Позиций в корзине: ' . $amountProducts . ' </br>
					Количество товара в корзине: ' . $amountPositions . ' </br>
					Итого: ' . number_format($totalPrice) . ' руб.
					<!--
					<select class="form-select" aria-label="Адрес магазина">
						<option selected="true" disabled="disabled">Выберите адрес магазина</option>
						<option value="ул. Созидателей 13">ул. Созидателей 13</option>
						<option value="ул. Пушкарёва 43">ул. Пушкарёва 43</option>
						<option value="ул. Автозаводская 32">ул. Автозаводская 32</option>
					</select>
					-->
					<a class="btn btn-primary" name="category" href="create_order.php?tp=' . $totalPrice . '">Оформить заказ</a>
				</div>
				<div class="col">
					<div class="row d-flex gap-3 justify-content-start">
			';
			$one = $conn->query("SELECT cards.id, cards.name, cards.description, cards.image, cards.category_id, cards.price, cards.qty, category.title, cart.cart_qty FROM cards INNER JOIN category on cards.category_id = category.id INNER JOIN cart on cards.id = cart.product_id WHERE cart.user_id like $userId");
			foreach ($one as $row) {
				echo '
                	<div class="card p-3" style="width: 18rem;">
                			<img src="images/' . $row["image"] . '" class="card-img-top" alt="...">
                			<div class="card-body">
                				<h5 class="card-title">' . $row["name"] . '</h5>
                				<p class="card-text">Цена: ' . number_format($row["price"]) . ' руб.</p>
                				<p class="card-text">Категория: ' . $row["title"] . '</p>
                				<p class="card-text">В наличие: ' . $row["qty"] . '</p>
								<div class="d-flex mb-4" w-100>
									<a class="btn btn-primary px-3 me-2" id="minusProduct" href="add_to_cart.php?qty=' . $row["cart_qty"] - 1 . '&id=' . $row["id"] . '">
										-
									</a>
				
									<div class="form-outline w-100">
										<input id="labelamount" min="1" max="' . $row["qty"] . '" name="quantity" value="' . $row["cart_qty"] . '" type="number" class="form-control text-center" />
									</div>
				
									<a class="btn btn-primary px-3 ms-2" id="plusProduct" href="add_to_cart.php?qty=' . $row["cart_qty"] + 1 . '&id=' . $row["id"] . '">
										+
									</a>
							  	</div>
                				<a href="more.php?id=' . $row["id"] . '" class="btn btn-primary w-100">Подробнее</a>
                			</div>
                	</div>
                ';
			}
			echo '
					</div>
				</div>';
			?>
		</div>
	</div>
	<?php require "UI/footer.php"; ?>
	</div>
	<script type="text/javascript" src="js/app.js"></script>
</body>

</html>