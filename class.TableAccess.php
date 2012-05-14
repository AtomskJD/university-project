<?php
// Абстрактный класс для доступа к таблицам
abstract class TableAccess {
    const USER_NAME = 'root';
    const USER_PASS = 'pass@word1';
    const DB_HOST   = 'localhost';
    const DB_NAME   = 'mydb';
    protected $_db;
    
    protected $table_name = 'none';
    protected $columns = array(); //

    abstract public function getData();
    // abstract public function setData();
    abstract public function getInfo(); // Получаем имя и описание таблицы
    
    function __construct()
    {
        // Инициализируем соединение с базой
        try {
            $conn = "mysql:host=" .self::DB_HOST .";dbname=". self::DB_NAME; 
            $this->_db = new PDO($conn, self::USER_NAME, self::USER_PASS);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}
?>