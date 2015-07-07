<?php
$routing = (strip_tags(trim($_GET['routing'])));

switch($routing){
    case 'create':
        require "inc/create_news.inc.php";
        break;

    default:require "inc/get_news.inc.php";
}