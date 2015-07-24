<style>
    .addNews {
        display:none !important;
    }
</style>
<?php
    $category_id = (int)(strip_tags(trim($_GET["category_id"])));
    $res = array();

    switch($category_id){
        case "0":
            $res = $news->getAllNews();
            break;
        case "1":
            $res = $news->getNewsForCategory($category_id);
            $category_name = "Политика";
            break;
        case "2":
            $res = $news->getNewsForCategory($category_id);
            $category_name = "Культура";
            break;
        case "3":
            $res = $news->getNewsForCategory($category_id);
            $category_name = "Спорт";
            break;
        case "4":
            $res = $news->getNewsForCategory($category_id);
            $category_name = "IT";
            break;
    }
    foreach($res as $out){
        $date = date("d-m-Y: H-i-s",$out[datetime]);
        if(!$out["category"]){
            $out["category"] = $category_name;
        }
        echo<<< "OUT"
            <li class=me>
                <div class=name>
                  <span ><a href="http://news/news.php?routing=delete&del=$out[id]">Удалить</a></span>
                </div>
                <div class=message>
                  <b>$out[title]</b></br></br>
                  <p>$out[description]</p>
                  <div class=msg-time>
                    $date <br><br>
                    #$out[category]
                  </div>
                </div>
              </li>
        
OUT;
    }
?>
