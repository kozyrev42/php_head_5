<head>
    <meta charset="utf-8">
    <title> 5 глава </title>
    <style></style>
</head>

<br />

<h3>Добавь свой рейтинг</h3>

<hr />

<?php

// функция импортирует php-сценарий из другого файла.php 

use function PHPSTORM_META\type;

require_once('appvars.php');
require_once('connectvars.php');


// функция создаёт и инициализирует константу
// создание константы, которая содержит имя каталога для загружаемых файлов
// define('GW_UPLOADPATH', 'images/');  
// импортировали из файла 'appvars.php'

if (isset($_POST['submit'])) {      //   если с $_POST прилетели данные, выполняется блок
                                    //   при первой итерации, данных с $_POST нет, выполняется блок ELSE {}
    $name = $_POST['name'];
    $score = $_POST['score'];
    $photo = $_FILES['photo'] // указываем значение атрибута name="" тэга из которого хотим что-то взять
                    ['name']; // указываем что из массива берём имя файла

    $photo_type = $_FILES['photo']['type'];  //  в переменной сохраняем тип загружаемого файла 
    $photo_size = $_FILES['photo']['size'];  //  в переменной сохраняем размер загружаемого файла


    if ($photo_size <= GW_MAXFILESIZE) {        // проверка на размер файла
        
        // проверим на наличие поступивших данных
        // если не пустые значение выполняем блок
        if (!empty($name) && !empty($score) && !empty($photo)) {

            // time() . $photo - образуют новое имя файла   
            $newNameFile = time() . $photo;                                             //!
            // имя католога объединяем с Именем файла > образую путь
            $target = GW_UPLOADPATH . $newNameFile;                                     //!

            // далее в условии перемещаем файл в директорию
            // если файл перемещен подключаемся к базе
            // в функции перемещения указываем от куда и куда перемещаем загружаемый файл
            // $_FILES['photo']['tmp_name'] - извлекаем полное имя файла из массива, где ['photo'] - поле таблицы
            if (move_uploaded_file($_FILES['photo']['tmp_name'],$target)) {
                $dbConnect = mysqli_connect(HOST, USER, PASSWORD, DB_NAME)
                    or die('Ошибка соединения с Сервером')
                ;

                $query = "INSERT INTO `score_list` (     /* запрос обязательно в двойных кавычках  */
                    `date`,`name`,`score`,`images`)
                    VALUES (now(),'$name','$score','$newNameFile')"        //!
                ;        

                $result = mysqli_query($dbConnect, $query)         //  mysqli_query - принимает 2 аругумента:
                    or die ('Ошибка при выполнении запроса к БД')  //  первый:-> ссылка на соединение
                ; 
                
                mysqli_close($dbConnect);

                echo  'тип файла'. $photo_type .'<br/>';   
                echo  'размер файла'. $photo_size .'<br/>'; 

                echo 'данные пришли, по средствам $_POST'.'<br/>';
                echo 'Спасибо что добавили свой рейтинг'.'<br/>';
                echo 'Имя:'. $name. '<br/>';
                echo 'Рейтинг:'. $score .'<br/>';
                echo '<img src="' . GW_UPLOADPATH . $newNameFile . '" alt="изображение.>"';
            }
        } else {
            echo 'не введено Имя или Рейтинг или не добавлено изображение'.'<br/>';
        }
    } else {
        echo 'изображение больше 32 kb'.'<br/>';
    }         
} else {
        echo 'данные НЕ пришли, выводим форму'.'<br/>';
?>  
    <!-- enctype="multipart/form-data" - обязательный атрибут, сообщает форме какое кодирование данных использовать, при отправке на сервер-->
    <form enctype="multipart/form-data"  
        method="post" action="add_score.php">

    <!-- устанавливаем максимальный размер файла для загрузки 1000000 байт - 1 мб -->
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/> 

    <label for="name">Имя</label> 
    <input type="text" name="name" id="name" /><br /> <br />

    <label for="score">Рейтинг</label> 
    <input type="text" name="score" id="score" /> <br /> <br />

    <!-- форма для выбора файла -->
    <label for="photo"> Файл изображения </label>
    <input type="file" id="photo" name="photo"/> <br /> <br />

    <input id="submit" name="submit" type="submit" value="Добавить" /><br />

    </form>

<?php
 };
?>

<hr />
<a href="index.php"> << Назад к списку рейтинга </a>