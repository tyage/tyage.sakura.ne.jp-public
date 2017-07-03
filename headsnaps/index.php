<?php
require_once("./lib.php");

$db = new Database();
$pdo = $db->connect();

$sql = "select * from headsnaps order by uploaded desc limit 0,20";
$stmt = $pdo->prepare($sql);
$stmt->execute();
?>
<html>
	<head>
		<link rel="stylesheet" href="./default.css">
	</head>
	<body>
		<div id="photos">
			<?php while($photo = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
				<div class="photo">
					<a href="<?= h($photo["url"]) ?>">
						<img src="<?= h($photo["image_url"]) ?>">
					</a>
				</div>
			<?php endwhile; ?>
		</div>
	</body>
</html>