<?php
function __autoload($name){
    require "class/$name.class.php";
}

$news = new NewsDB();
$errorMsg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    require "inc/save_news.inc.php";
}
if($_SERVER["REQUEST_METHOD"] == "GET"){
    require "inc/delete_news.inc.php";
}
?>
<!DOCTYPE html>
<html >
  <head>
     <? require "inc/head.inc.php"; ?>
  </head>
  <body>
    <?
        require "inc/body_header.inc.php";
        require "inc/routing.inc.php";
        require "inc/body_footer.inc.php";
    ?>
  </body>
</html>
