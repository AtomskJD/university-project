<?php
// Абстрактный класс для доступа к таблицам
class TableAccess {
    const USER_NAME = 'root';
    const USER_PASS = 'pass@word1';
    const DB_HOST   = 'localhost';
    const DB_NAME   = 'mydb';
    protected $_db;

    function getData()
    {
        //Получаем данные из таблицы
        $sql = "SELECT * FROM storages";
        foreach($this->_db->query($sql) as $row){
            print_r($row);
        }

    }
    function setData()
    {
        // Запись данных в таблицу
    }
    function getInfo()
    {
        // Получаем имя и описание таблицы
        echo "<h1>Все ОК</h1>";
    }
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
$myTest = new TableAccess();
$myTest->getData();
$myTest->getInfo();
?>