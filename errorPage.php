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
	<div class="container features d-flex justify-content-center align-items-center">
		<?php
		require "pdo_connection.php";
		if (isset($_GET['msg'])) {
			$alerText = $_GET['msg'];
		}
		echo '
                <div>
                    <h1>' . $alerText . '</h1>
                </div>
            ';
		?>
	</div>
	</div>
	<?php require "UI/footer.php"; ?>
	</div>
</body>

</html>