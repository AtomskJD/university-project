<?php
// Абстрактный класс для доступа к таблицам
abstract class TableAccess {
    // Параметры соединения с бызой
    const USER_NAME         = 'root';
    const USER_PASS         = 'pass@word1';
    const DB_HOST           = 'localhost';
    const DB_NAME           = 'mydb';
    protected $_db;
    
    // Заглушки параметров таблицы, использование через методы в конкретных классах
    protected $table_name   = 'Name';
    protected $table_title  = 'Title';
    protected $table_prop   = array();
    protected $table_count  = 0;
    
    // Секция абстрактных методов для таблиц все методы возвратные
    abstract public function getData();         // Возврат значений таблицы
    abstract public function setData($prop);    // Вставка данных
    abstract public function getTitle();        // Заголовок таблицы
    abstract public function getInfo();         // Получаем имя и описание таблицы
    abstract public function getTableProp();    // Возвращает двумерный массив свойств полей таблицы
    abstract public function getCount();        // Возвращает число записей
    
    function getHeaders(){
        foreach ($this->getTableProp() as $headers){
            $out[] = $headers['t_name'];
        }
        return $out;
    }
    function __construct()
    {
        // Инициализируем соединение с базой
        try {
            $conn = "mysql:host=" .self::DB_HOST .";dbname=". self::DB_NAME; 
            $this->_db = new PDO($conn, self::USER_NAME, self::USER_PASS);
            $this->_db->exec('SET NAMES utf8');
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}
?>