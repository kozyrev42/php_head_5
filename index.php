<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title> 5 глава </title>
	<style></style>
</head>

<body>

	<a href="admin.php"> Админ </a>
	<br />
	<h3> Гитарные войны. Список рейтингов. </h3>
	<a href="add_score.php"> Добавить свой рейтинг </a>
	<hr />

<?php
	// функция импортирует php-сценарий из другого файла.php 
	require_once('appvars.php');
	require_once('connectvars.php');


	// создание константы со значением пути до каталога
	// define('GW_UPLOADPATH', 'images/');		

	$dbConnect = mysqli_connect(HOST, USER, PASSWORD, DB_NAME)
		or die('Ошибка соединения с Сервером');

	// $query = "SELECT * FROM `score_list`";
	$query = "SELECT * FROM `score_list` ORDER BY `score` DESC";

	$data_query = mysqli_query($dbConnect, $query);
	if (!$data_query) {
		echo 'Ошибка запроса: ' . mysqli_error($dbConnect) . '<br>';
		echo 'Код ошибки: ' . mysqli_errno($dbConnect);
	} else {
		echo 'запрос успешен' . '<br><br>';    // выполнился
		while ($row = $data_query->fetch_assoc()) {
			// вывод данных рейтинга
			echo 'рейтинг:'.'<b>'. $row['score'].'</b>' . '<br>';
			echo 'имя:'. $row['name'] . '<br>';
			echo 'дата:'. $row['date'] . '<br>'. '<br>';
			echo '<tr> <td> <img src="' . GW_UPLOADPATH . $row['images'].'" alt="Подтверждено"/></td></tr>' . '<br>' . '<br>';
		}
	}
	mysqli_close($dbConnect);
?>
</body>
</html>