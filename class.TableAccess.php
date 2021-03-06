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
    protected $table_name   = 'Default Name';
    protected $table_title  = 'Title';
    protected $table_prop   = array('Default prop');
    protected $table_count  = null;
    protected $table_info   = 'DEFAULT TABLE INFO';
    
    // Секция абстрактных методов для таблиц все методы возвратные
    abstract public function getData();         // Возврат значений таблицы
    abstract public function getCount();        // Возвращает число записей
    abstract public function setData($prop);    // Вставка данных
    abstract public function deleteRow($param); // Удаление строки из представления
    
    public function showError($err){
        return "<span id='ERR'>".$err[2]."</span>";
    }
    
    public function getTableName()
    {
        return $this->table_name;
    }
    
    public function getTitle()                  // Заголовок таблицы
    {
        return $this->table_title;
    }
    
    public function getInfo()                   // Получаем имя и описание таблицы
    {
        return $this->table_info;
    }
    
    public function getTableProp()              // Возвращает двумерный массив свойств полей таблицы
    {
        return $this->table_prop;    
    }
    
    function getHeaders($table)                       // Возвращает переведенные заголовки таблицы из свойств таблицы 
    {
        foreach ($this->table_prop as $headers){
            if ($headers['show'])
            $out[0][] = $headers['t_name'];
        }
        if ($table != 'Audit')
            $out[0][] = 'удалить';
        return $out;
    }
    
    function getPrimaryKey()
    {
        $out = array();
        foreach ($this->table_prop as $primary_key){
            if (array_key_exists('pkey', $primary_key)){
                $out[] = $primary_key['name'];
            }
        }
        return $out;
    }
    
   /**
 *  function getForeginKey($fkey)                    // TODO: заглушка для шаблона
 *     {
 *         return 1;
 *     }
 */
    
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
    // TODO: перенести setData() в родительский класс

}
?>