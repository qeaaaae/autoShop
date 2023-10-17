<?php
require "pdo_connection.php";
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = 6;
$offset = ($page - 1) * $itemsPerPage;
?>
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
			if (isset($_POST['category']) && $_POST['category'] !== "") {
				$currentCategory = $_POST['category'];
				$one = $conn->query("SELECT cards.id, cards.name, cards.description, cards.image, cards.category_id, cards.price, cards.qty, category.title FROM cards INNER JOIN category on cards.category_id = category.id WHERE category_id = '$currentCategory' LIMIT $itemsPerPage OFFSET $offset");
				$totalItems = $conn->query("SELECT COUNT(*) FROM cards WHERE category_id = '$currentCategory'")->fetch();
			} else {
				$totalItems = $conn->query("SELECT COUNT(*) FROM cards")->fetch();
				$one = $conn->query("SELECT cards.id, cards.name, cards.description, cards.image, cards.category_id, cards.price, cards.qty, category.title FROM cards INNER JOIN category on cards.category_id = category.id LIMIT $itemsPerPage OFFSET $offset");
			}
			$totalPages = ceil($totalItems["COUNT(*)"] / $itemsPerPage);
			$categories = $conn->query("SELECT * FROM category");
			echo '
				<form class="col-3 custom__category" action="index.php" method="POST">
					<h3>Категории:</h3>
			';
			foreach ($categories as $row) {
				echo '
						<button class="btn btn-outline-primary text-break w-75" name="category" value="' . $row["id"] . '">' . $row["title"] . '</button>
				';
			}
			echo '
					<button class="btn btn-outline-primary text-break w-75" name="category" value="">Все</button>
				</form>
				<div class="col">
					<div class="row d-flex gap-3 justify-content-start">
			';
			foreach ($one as $row) {
				if (!isset($row['id'])) {
					echo '<h3>Товара данной категории сейчас нет!</h3>';
				} else {
					echo '
						<div class="card p-3" style="width: 18rem;">
								<img src="images/' . $row["image"] . '" class="card-img-top" alt="...">
								<div class="card-body">
									<h5 class="card-title">' . $row["name"] . '</h5>
									<p class="card-text">Цена: ' . number_format($row["price"]) . ' руб.</p>
									<p class="card-text">Категория: ' . $row["title"] . '</p>
									<p class="card-text">В наличие: ' . $row["qty"] . '</p>
									<a href="more.php?id=' . $row["id"] . '" class="btn btn-primary">Подробнее</a>
								</div>
						</div>
					';
				}
			}
			echo '
					</div>';
			?>
			<nav aria-label="Page navigation example" id="pagination" class="mt-3">
				<ul class="pagination justify-content-center">
					<?php
					if ($page > 1) {
						echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Назад</a></li>';
					}
					for ($i = 1; $i <= $totalPages; $i++) {
						$activeClass = ($i == $page) ? 'active' : '';

						echo '<li class="page-item ' . $activeClass . '">';
						echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
						echo '</li>';
					}
					if ($page < $totalPages) {
						echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Вперед</a></li>';
					}
					?>
				</ul>
			</nav>
			<? echo '</div>'; ?>
		</div>
	</div>
	<?php require "UI/footer.php"; ?>
	</div>
</body>

</html>