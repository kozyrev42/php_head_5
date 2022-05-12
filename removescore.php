<!DOCTYPE html>
<head>
	
</head>

<body>
<?php 		
// скрипт для удаления рейтинга из базы данных
require_once('appvars.php');
require_once('connectvars.php');


// назначение переменным поступившими данными
// если в сценарий приходит GET запрос, извлекаем из него данные
if (isset($_GET['id']) && 
    isset($_GET['date']) && 
    isset($_GET['name']) &&
	isset($_GET['score']) && 
    isset($_GET['images']))   
	{
	// извлечение данных рейтинга из суперглобального массива $_GET
	$id = $_GET['id'];
	$date = $_GET['date'];
	$name = $_GET['name'];
	$score = $_GET['score'];
	$screenshot = $_GET['images'];
    echo '<p > GET ПОЛУЧЕН </p>';
}
else if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['score'])) {
	// ИНАЧЕ извлечение данных из массива $_POST
	$id = $_POST['id'];
	$name = $_POST['name'];
	$score = $_POST['score'];
    $screenshot = $_POST['screenshot'];
    echo '<p > POST ПОЛУЧЕН </p>';
}
else {
	echo '<p class="error"> Ошибка </p>';
}


// если данные пришли по средствам $_POST из формы в этом же скрипте, в результате события 'submit'
if (isset($_POST['submit'])) {
	if ($_POST['confirm']=='yes') 	// если чекбокс активирован, выполнется блок
		{
        @unlink (GW_UPLOADPATH . $screenshot);
		// удаление с сервера > файла изображения с помощью функции unlink()
		// функция принимает в аргументе путь до файла, который нужно удалить
		// @ - дериктива, подавляющая вывод сообщения об ошибке
		// это необходимо при вызове функции во избежания возврата ошибки E_WARNING
		// так как, вызов функции, может быть для несуществующего файла

		
		$dbc = mysqli_connect(HOST, USER, PASSWORD, DB_NAME);

		// удаление рейтинга из БД
		$query = "DELETE FROM score_list WHERE id = $id";
		mysqli_query($dbc, $query);
		mysqli_close($dbc);

		echo '<p> Рейтинг удалён! </p>';
	}
	else {
		echo '<p> Рейтинг не удалён! </p>';
	}
}
// если данные $_POST в результате события 'submit' не пришли > тогда форму на подтверждение удаления
else if (isset($id) && isset($name) && isset($date)
		&& isset($score) && isset($screenshot))  
		{
	echo '<p> Вы уверены, что хотите удалить этот рейтинг?</p>';
	echo '<p> Имя:' . $name . '<br/> Дата:' . $date .
		  '<br/> Рейтинг:' . $score .
          '<br/> изображение:' . $screenshot .
          '</p>';
	echo '<form method="post" action="removescore.php">';
	echo '<input type="radio" name="confirm" value="yes"/> Да ';
	echo '<input type="radio" name="confirm" value="no" checked="checked" /> Нет <br/> ';
	echo '<input type="submit" value="Удалить" name="submit" />';
	echo '<input type="hidden" name="id" value="'. $id .'"/>';
    echo '<input type="hidden" name="name" value="'. $name .'"/>';
	echo '<input type="hidden" name="score" value="'. $score .'"/>';
    echo '<input type="hidden" name="screenshot" value="'. $screenshot .'"/>';
	echo '</form>';	
} 
	echo '<p> <a href="admin.php"> &lt; Назад к списку рейтингов </a> </p>';

?>

</body>
</html>