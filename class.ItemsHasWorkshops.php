<?php
class ItemsHasWorkshops extends TableAccess {
    protected $table_name   = "items_has_workshops";
    protected $table_title  = "Какими цехами какой товар производится";
    protected $table_info   = "таблица производственного отношения продукции и цеха производства";
    protected $table_count  = "none";
    protected $table_prop   = array(                                       
                                        array(
                                            'name'  => 'items_item_id',                    //что идет в таблицу $param[0]
                                            't_name'=> 'Название продукции',
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => array(
                                                            'fkey_table'=>'items',
                                                            'fkey_name'=>'item_name',
                                                            'fkey_id'=>'item_id'     //соответствие из ВК
                                                            ) 
                                            ),
                                        array(
                                            'name'  => 'workshops_workshop_id',                    //что идет в таблицу $param[1]
                                            't_name'=> 'Название цеха',
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => array(
                                                            'fkey_table'=>'workshops',
                                                            'fkey_name'=>'workshop_name',
                                                            'fkey_id'=>'workshop_id'     //соответствие из ВК
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
            $query = $this->_db->prepare("SELECT COUNT(*) FROM items_has_workshops");
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
        $sql = "SELECT items_item_id, workshops_workshop_id, item_name, workshop_name FROM items_has_workshops 
            INNER JOIN items
                ON items_has_workshops.items_item_id = items.item_id
            INNER JOIN workshops
                ON items_has_workshops.workshops_workshop_id = workshops.workshop_id 
            ORDER BY workshops_workshop_id, item_name";
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
            $query = $this->_db->prepare("INSERT INTO items_has_workshops VALUES (:item_id, :workshop_id)");
            
            $query->bindParam(':item_id', $prop['items_item_id']);
            $query->bindParam(':workshop_id', $prop['workshops_workshop_id']);
            $query->execute() or die (print_r($query->errorInfo()) . ' <a href="?id=itemshasworkshops">НАЗАД</a>');
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
    
    public function deleteRow($param)
    {
        //var_dump($param);exit;
        $query = $this->_db->prepare("DELETE FROM items_has_workshops WHERE items_item_id = :items_item_id AND workshops_workshop_id = :workshops_workshop_id");
        $query->bindParam(':items_item_id', $param[0]);
        $query->bindParam(':workshops_workshop_id', $param[1]);
        
        $query->execute() or die(print_r($query->errorInfo()) . ' <a href="?id=itemshasworkshops">НАЗАД</a>');
    }
}
?>