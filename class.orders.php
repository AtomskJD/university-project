<?php
class ItemsHasWorkshops extends TableAccess {
    protected $table_name   = "items_has_workshops";
    protected $table_title  = "Какими цехами какой товар производится";
    protected $table_info   = "таблица производственного отношения продукции и цеха производства";
    protected $table_count  = "none";
    protected $table_prop   = array(                                       
                                        array(
                                            'name'  => 'items_item_id',                    //что идет в таблицу
                                            't_name'=> 'Название продукции',
                                            'fkey'  => array(
                                                            'fkey_table'=>'items',
                                                            'fkey_name'=>'item_name',
                                                            'fkey_id'=>'item_id'     //соответствие из ВК
                                                            ) 
                                            ),
                                        array(
                                            'name'  => 'workshops_workshop_id',                    //что идет в таблицу
                                            't_name'=> 'Название цеха',
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
        $sql = "SELECT item_name, workshop_name FROM items_has_workshops 
            INNER JOIN items
                ON items_has_workshops.items_item_id = items.item_id
            INNER JOIN workshops
                ON items_has_workshops.workshops_workshop_id = workshops.workshop_id ORDER BY item_name";
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
            $query = $this->_db->prepare("INSERT INTO items_has_workshops VALUES (?, ?)");
            $query->execute($prop) or die (print_r($query->errorInfo()) );
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}
?>