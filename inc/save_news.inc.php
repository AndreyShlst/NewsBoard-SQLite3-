<?php
//Фильтруем данные из формы
$title = $news->filterPostData($_POST["title"]);
$description = $news->filterPostData($_POST["description"]);
$source = $news->filterPostData($_POST["source"]);
$category = abs((int)$_POST["category"]);

//Проверяем есть ли необходимые данные и корректно ли сохраняется новости
if(empty($title)||empty($description)){
    $errMsg = "Заполните все поля!";
}else{
    if(!$news->saveNews($title,$category,$description,$source)){
        $errMsg = "Ошибка при добавлении новости";
    }else{
        header("Location: news.php");
        exit;
    }

}
