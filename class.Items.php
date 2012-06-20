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
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => 0),
                                        array(
                                            'name'  => 'item_name',
                                            't_name'=> 'Название товара',
                                            'show'  => 1,
                                            'fkey'  => 0),
                                        array(
                                            'name'  => 'storage_id',                    //что идет в таблицу
                                            't_name'=> 'Название склада',
                                            'show'  => 1,
                                            'fkey'  => array(
                                                            'fkey_table'=>'storages',
                                                            'fkey_name'=>'storage_name',
                                                            'fkey_id'=>'storage_id'     //соответствие из ВК
                                                            ) 
                                            ),
                                        array(
                                            'name'  => 'unit_id',                    //что идет в таблицу
                                            't_name'=> 'единица измерения',
                                            'show'  => 1,
                                            'fkey'  => array(
                                                            'fkey_table'=>'units',
                                                            'fkey_name'=>'unit_name',
                                                            'fkey_id'=>'unit_id'     //соответствие из ВК
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
        $sql = "SELECT item_id, item_name, storage_name, unit_name FROM items 
        INNER JOIN storages
            ON items.storage_id = storages.storage_id
        INNER JOIN units
            ON units.unit_id = items.unit_id ORDER BY item_id";
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
            $query = $this->_db->prepare("INSERT INTO items(item_id, item_name, storage_id, unit_id) 
            VALUES (:item_id, :item_name, :storage_id, :unit_id)");
            
            $query->bindParam(':item_id', $prop['item_id']);
            $query->bindParam(':item_name', $prop['item_name']);
            $query->bindParam(':storage_id', $prop['storage_id']);
            $query->bindParam(':unit_id', $prop['unit_id']);
            
            $query->execute() or die (print_r($query->errorInfo()) );
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
    
    public function deleteRow($param)
    {
        $query = $this->_db->prepare("DELETE FROM items WHERE item_id = :item_id");
        $query->bindParam(':item_id', $param[0]);
        
        $query->execute() or die(print_r($query->errorInfo()));
    }
}
?>