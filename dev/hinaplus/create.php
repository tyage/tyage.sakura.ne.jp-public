<?php
$title = 'ひなプラス';

require_once('/home/tyage/lib.php');
$pdo = Database::connect();

$stmt = $pdo->prepare('INSERT INTO `hinaplus` (`chara`, `created`) VALUES ("☃", now())');
$stmt->execute();

$stmt = $pdo->prepare('SELECT LAST_INSERT_ID() as `id`');
$stmt->execute();
$fetch = $stmt->fetch();
$id = $fetch['id'];
?>

<script>
	document.location = 'view.php?id=<?= $id ?>'
</script>