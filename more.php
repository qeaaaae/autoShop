<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Магазин</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<link href="main.css" rel="stylesheet">
</head>

<body>
	<?php require "UI/navbar.php"; ?>
	<div class="container features d-flex justify-content-center align-items-start">
		<?php
		require "pdo_connection.php";
		$id = $_GET['id'];
		$one = $conn->query("SELECT * FROM cards where id like $id");
		foreach ($one as $row) {
			echo '
				<div class="product-item">
					<img src="images/' . $row["image"] . '" class="product__image">
					<div class="product-item__info">
						<h3>' . $row["name"] . '</h3>
						<p class="fs-6">' . $row["description"] . '</p>
						<p class="fs-5">Цена: ' . number_format($row["price"]) . ' рублей</p>
						<a href="add_to_cart.php?id=' . $row["id"] . '" class="btn btn-primary w-100">Добавить в корзину</a>
					</div>
				</div>
				';
		}
		?>
	</div>
	</div>
	<?php require "UI/footer.php"; ?>
</body>

</html>