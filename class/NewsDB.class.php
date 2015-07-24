<?php
require_once "INewsDB.class.php";
class NewsDB implements INewsDB{
    const DB_NAME = "./db/news.db";// имя базы данных
    const RSS_NAME = "./rss/rss.xml";// имя RSS-файла
    const RSS_TITLE = "Новостная лента";// Заголовок ленты
    const RSS_LINK = "http://news/news.php";// ссылка на ленту
    private $_db = null;//для хранения экземпляра класса SQLite3.

    //Конструктор
    function __construct(){
        $this->_db = new SQLite3(self::DB_NAME);
        if(is_file(self::DB_NAME)&& filesize(self::DB_NAME) == 0){//Проверяем существует ли БД(создался ли файл) и проверяем его размер
            try{
                # Таблица msgs
                $sql = "CREATE TABLE msgs(
	                                  id INTEGER PRIMARY KEY AUTOINCREMENT,
	                                  title TEXT,
	                                  category INTEGER,
                                      description TEXT,
                                      source TEXT,
                                      datetime INTEGER)";
                if(!$this->_db->exec($sql)){
                    throw new Exception($this->_db->lastErrorMsg());
                }

                # Таблица category
                $sql = "CREATE TABLE category(
                                          id INTEGER,
                                          name TEXT)";
                if(!$this->_db->exec($sql)){
                    throw new Exception($this->_db->lastErrorMsg());
                }

                # Заполнение таблицы category
                $sql = "INSERT INTO category(id, name)
                    SELECT 1 as id, 'Политика' as name
                    UNION SELECT 2 as id, 'Культура' as name
                    UNION SELECT 3 as id, 'Спорт' as name
                    UNION SELECT 4 as id, 'IT' as name ";
                if(!$this->_db->exec($sql)){
                    throw new Exception($this->_db->lastErrorMsg());
                }
             }catch(Exception $e){
               echo "Ошибка =/";
             }
        }

    }

    //Деструктор
    function __destruct(){
        unset($this->_db);
    }

    //Геттер для private $_db
    function __get($name){
        if($name == "db"){//Даем возможность обратится к свойству как db
            return $this->_db;
        }
        throw new Exception ("Unknown property");
    }

    //Фильтр данных из формы
    function filterPostData($data){
        $data = strip_tags($data);
        return $this->_db->escapeString($data);
    }

    //Ф-я сохранения новости в БД
    function saveNews($title, $category, $description, $source){
        $dt = time();
        $sql = "INSERT INTO msgs(
                                  title,
                                  category,
                                  description,
                                  source,
                                  datetime)
                VALUES(
                        '$title',
                        $category,
                        '$description',
                        '$source',
                        $dt)";
        $res = $this->_db->exec($sql);
        if(!$res){
            return false;
        }else{
            $this->createRss();
            return true;
        }
    }

    //Ф-я преобразования результата sql-запроса к массиву
    private function sqlToArray($data){
        $array = array();
        while($row = $data->fetchArray(SQLITE3_ASSOC)) {
            $array[] = $row;
        }
        return $array;
    }

    //Ф-я выборки всех данных
    function getAllNews(){
        try{
            $sql = "SELECT msgs.id as id,
                           title,
                           category.name as category,
                           description,
                           source,
                           datetime
                    FROM msgs, category
                    WHERE category.id = msgs.category
                    ORDER BY msgs.id DESC";

            $res = $this->_db->query($sql);
            if(!$res){
                throw new Exception ($this->_db->lastErrorMsg());
            }
        }catch (Exception $e){
            $errMsg = "Произошла ошибка при выводе ленты новостей";
            return $errMsg;
        }
        return $this->sqlToArray($res);
    }

    //Ф-я выборки данных по категории
    function getNewsForCategory($id){
        try{
            $sql = "SELECT msgs.id as id,
                           title,
                           description,
                           source,
                           datetime
                    FROM msgs
                    WHERE msgs.category = $id
                    ORDER BY msgs.id DESC";

            $res = $this->_db->query($sql);
            if(!$res){
                throw new Exception ($this->_db->lastErrorMsg());
            }
        }catch (Exception $e){
            $errMsg = "Произошла ошибка при выводе ленты новостей";
            return $errMsg;
        }
        return $this->sqlToArray($res);
    }

    //Ф-я удаления данных
    function deleteNews($id){
            $remove_query = "DELETE FROM msgs WHERE msgs.id = $id";
            $res = $this->_db->query($remove_query);
        if(!$res){echo "Error";}
    }

    //Ф-я формирования rss-документа(c помощью интерфейса DOM)
    private function createRss(){
        $dom = new DOMDocument("1.0","utf-8");//экземпляр класса DOMDocument
        $rss = $dom->createElement("rss");//корневой элемент rss
        $dom->appendChild($rss);//привязали  его к объекту $dom

        #Для нормального форматирования документа..
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;

        $version = $dom->createAttribute("version");//Cоздали атрибут version
        $version->value = '2.0';
        $rss->appendChild($version);//связали его с корневым елементом

        $channel = $dom->createElement("channel");
        $title = $dom->createElement("title",self::RSS_TITLE);
        $link = $dom->createElement("link",self::RSS_LINK);
        $channel->appendChild($title);
        $channel->appendChild($link);
        $rss->appendChild($channel);

        $contents = $this->getAllNews();
        if(!$contents){
            return false;
        }

        foreach($contents as $news){//Формируем отдельные элементы
            $item = $dom->createElement("item");
            $title = $dom->createElement("title",$news['title']);
            $category = $dom->createElement("category",$news['category']);

            $description = $dom->createElement("description");
            $cdata  = $dom->createCDATASection($news['description']);
            $description->appendChild($cdata);

            $link = $dom->createElement("link","#");
            $date = date("m.d.y",$news["datetime"]);
            $publicDate = $dom->createElement("publicDate",$date);

            $item->appendChild($title);
            $item->appendChild($link);
            $item->appendChild($description);
            $item->appendChild($publicDate);
            $item->appendChild($category);

            $channel->appendChild($item);


        }
        $dom->save(self::RSS_NAME);

    }
}
