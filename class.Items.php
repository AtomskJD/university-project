<?php
class Items extends TableAccess {
    protected $table_name   = "items";
    protected $table_title  = "продукция";
    protected $table_info   = "таблица содержит каталог выпускаемой продукции";
    protected $table_count  = "none";
    protected $table_prop   = array(
                                        array(
                                            'name'  => 'item_id',
                                            't_name'=> 'Номер товара',
                                            'fkey'  => 0),
                                        array(
                                            'name'  => 'item_name',
                                            't_name'=> 'Название товара',
                                            'fkey'  => 0),
                                        array(
                                            'name'  => 'storage_id',                    //что идет в таблицу
                                            't_name'=> 'Название склада',
                                            'fkey'  => array(
                                                            'fkey_table'=>'storages',
                                                            'fkey_name'=>'storage_name',
                                                            'fkey_id'=>'storage_id'     //соответствие из ВК
                                                            ) 
                                            )
                                    );
    public function getForeginKey($fkey)
    {
        $query = $this->_db->prepare('SELECT '. $fkey['fkey_id'] .', '. $fkey['fkey_name'] .' FROM '.$fkey['fkey_table']);
        $query->execute() or die ("ERROR gettin FK");
        $out = $query->fetchAll(PDO::FETCH_ASSOC);
        return $out;
    }
    
    public function getCount()
    {
        try {   
            $query = $this->_db->prepare("SELECT COUNT(*) FROM items");
            $query->execute() or die ("ERROR");
            $result = $query->fetch(PDO::FETCH_NUM);
            return $result[0];
        } catch (PDOException $e) {
            return $e->getNessage();
        }

    }

	public function getData()
    {
        //Получаем данные из таблицы
        $sql = "SELECT item_id, item_name, storage_name FROM items INNER JOIN storages
        ON items.storage_id = storages.storage_id";
        $query = $this->_db->prepare($sql);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    public function setData($prop)
    {
        // Запись данных в таблицу
        //TODO: Возможно перенести в родительский класс
        try {
            var_dump($prop);
            $query = $this->_db->prepare("INSERT INTO items VALUES (?, ?, ?)");
            $query->execute($prop) or die (print_r($query->errorInfo()) );
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}
?>