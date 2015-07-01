<?php
require_once "INewsDB.class.php";
class NewsDB implements INewsDB{
    const DB_NAME = "./db/news.db";// имя базы данных
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
                    UNION SELECT 3 as id, 'Спорт' as name ";
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
        return $res;
    }

    //Ф-я преобразования результата sql-запроса к массиву
    private function sqlToArray($data){
        $array = array();
        while($row = $data->fetchArray(SQLITE3_ASSOC)) {
            $array[] = $row;
        }
        return $array;
    }

    //Ф-я выборки данных
    function getNews(){
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

    function deleteNews($id){
            $remove_query = "DELETE FROM msgs WHERE msgs.id = $id";
            $res = $this->_db->query($remove_query);
        if(!$res){echo "Error";}
    }
}

//$news = new NewsDB();