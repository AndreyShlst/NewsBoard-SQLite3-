<?php
    $res = array();
    $res = $news->getNews();
    foreach($res as $out){
        $date = date("d-m-Y: H-i-s",$out[datetime]);
        echo<<< "OUT"
            <li class=me>
                <div class=name>
                  <span ><a href="http://news/news.php?routing=delete&del=$out[id]">Удалить</a></span>
                </div>
                <div class=message>
                  <p>$out[description]</p>
                  <span class=msg-time>$date</span>
                </div>
              </li>
        
OUT;
    }
?>
