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
require_once('appvars.php');
require_once('connectvars.php');

/* ???
use function PHPSTORM_META\type; */

// функция создаёт и инициализирует константу
// создание константы, которая содержит имя каталога для загружаемых файлов
// define('GW_UPLOADPATH', 'images/');  
// импортировали из файла 'appvars.php'
// $_FILES - служит для сохранения информации о загруженных на веб-сервер файлах
if (isset($_POST['submit'])) {      //   если с $_POST прилетели данные, выполняется блок
                                    //   при первой итерации, данных с $_POST нет, выполняется блок ELSE {}
   $name = $_POST['name'];
   $score = $_POST['score'];
   $photo_name = $_FILES['photo'] // обращаемся к массиву 'photo' ,который вложен в $_FILES
                        ['name']; // обращаемся к имени загруженного файла, сохраняем его

   $photo_type = $_FILES['photo']['type'];  //  в переменной сохраняем тип загружаемого файла 
   $photo_size = $_FILES['photo']['size'];  //  в переменной сохраняем размер загружаемого файла


   if ($photo_size <= GW_MAXFILESIZE) {        // проверка на размер файла
      
      // проверим на наличие поступивших данных
      // если не пустые значение выполняем блок
      if (!empty($name) && !empty($score) && !empty($photo_name)) {

         // time() . $photo_name - образует новое уникальное имя файла
         // $newNameFile - его будем записывать в таблицу + другое
         $newNameFile = time() . $photo_name;       

         // ? имя католога объединяем с Именем файла > образуя полный путь для указания директории сохранения файла
         $target = GW_UPLOADPATH . $newNameFile;                                     

         // далее в условии перезаписываем файл в постоянный каталог с Новым Уникальным Именим
         // в функции перемещения указываем откуда и куда перезаписываем загружаемый файл
         // $_FILES['photo']['tmp_name'] - в аргументе функции - полное имя временного загруженного файла на веб-сервере
         // $target - место назначения файла - включает Имя постоянного каталога и Новое Уникальное Имя перезаписываемого файла
         if (move_uploaded_file($_FILES['photo']['tmp_name'],$target)) {
            // если файл Перезаписан записываем данные в Базу
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
   <!-- если данные не пришли > рендер формы для ввода данных -->
   <!-- enctype="multipart/form-data" - обязательный атрибут, сообщает форме какое кодирование данных использовать, при отправке на сервер-->
   <form enctype="multipart/form-data" method="post" action="add_score.php">

      <!-- устанавливаем максимальный размер файла для загрузки 1000000 байт - 1 мб -->
      <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/> 

      <label for="name">Имя</label> 
      <input type="text" name="name" id="name" /> <br/> <br/>

      <label for="score">Рейтинг</label> 
      <input type="text" name="score" id="score" /> <br/> <br/>

      <!-- форма для выбора файла, выводит окно операционной системы -->
      <!-- в глобальном массиве $_FILES > создаётся массив "photo" благодаря: type="file" name="photo"-->
      <label for="photo"> Файл изображения </label>
      <input type="file" id="photo" name="photo"/> <br /> <br />

      <input id="submit" name="submit" type="submit" value="Добавить" /><br />
   </form>

<?php
}; // закрываем блок else
?>

<hr />
<a href="index.php"> << Назад к списку рейтинга </a>