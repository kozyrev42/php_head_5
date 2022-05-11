<!DOCTYPE html>

<head>
	<meta charset="utf-8">
	<title> Админ </title>
	<style>
		table {
			font-family: 'Open Sans', sans-serif;
			font-weight: bold;
		}

		th {
			padding: 10px 20px;
			background: #56433D;
			color: #F9C941;
			font-size: 0.9em;
		}

		td {
			vertical-align: middle;
			padding: 10px;
			font-size: 14px;
			text-align: center;
			border: 2px solid #56433D;
		}
	</style>
</head>

<body>
	<h3>Страница администратора</h3>
	<a href="index.php"> Вернуться назад </a>

<?php
	require_once('appvars.php');
	require_once('connectvars.php');

	// соединение с базой
	$dbc = mysqli_connect(HOST, USER, PASSWORD, DB_NAME);

	// извлечение данных
	$query = "SELECT * FROM score_list ORDER BY score DESC";
	$data = mysqli_query($dbc, $query);

	// извлечение данных из массива рейтингов в цикле
	echo '<table>';
	while ($row = mysqli_fetch_array($data)) {
		echo '<tr><td>' . $row['id'] . '</td>';
		echo '<td>' . $row['name'] . '</td>';
		echo '<td>' . $row['date'] . '</td>';
		echo '<td>' . $row['score'] . '</td>';
		echo '<td>' . $row['images'] . '</td>';
		echo '<td> <a href="removescore.php?id=' . $row['id'] . '&amp;date=' . $row['date'] .
			'&amp;name=' . $row['name'] . '&amp;score=' . $row['score'] .
			'&amp;images=' . $row['images'] . '"> Удалить </a> </td> </tr>';
	}
	echo '</table>';

	mysqli_close($dbc);
?>


</body>

</html>