<?php
class Orders extends TableAccess {
    protected $table_name   = "orders";
    protected $table_title  = "Плановый выпуск";
    protected $table_info   = "Плановый выпуск продукции цехом";
    protected $table_count  = "none";
    protected $table_prop   = array(                                       
                                        array(
                                            'name'  => 'workshop_id',                    //что идет в таблицу
                                            't_name'=> 'Название цеха',
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => array(
                                                            'fkey_table'=> 'workshops',
                                                            'fkey_name' => 'workshop_name',
                                                            'fkey_id'   => 'workshop_id'     //соответствие из ВК
                                                       ) 
                                         ),
                                        array(
                                            'name'  => 'item_id',                    //что идет в таблицу
                                            't_name'=> 'Наименование продукции',
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => array(
                                                            'fkey_table'=> 'items',
                                                            'fkey_name' => 'item_name',
                                                            'fkey_id'   => 'item_id'
                                                       )
                                         ),
                                         array(
                                            'name'  => 'output_date',
                                            't_name'=> 'дедлайн',
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => 0
                                         ),
                                         array(
                                            'name'  => 'order_quantity',                    //что идет в таблицу
                                            't_name'=> 'Объем продукции',
                                            'show'  => 1,
                                            'fkey'  => 0
                                         ),
                                         array(
                                            'name'  => 'unit_name',                    //что идет в таблицу
                                            't_name'=> 'ед. измерения',
                                            'fkey'  => 0,
                                            'show'  => 1,
                                            'hide'  => 1
                                         )
                                    );
    public function getForeginKey($fkey)
    {
        $query = $this->_db->prepare('SELECT DISTINCT '. $fkey['fkey_id'] .', '. $fkey['fkey_name'] .' FROM '.$fkey['fkey_table']);
        $query->execute() or die ("ERROR gettin FK");
        $out = $query->fetchAll(PDO::FETCH_ASSOC);
        return $out;
    }
    
    public function getCount()
    {
        try {   
            $query = $this->_db->prepare("SELECT COUNT(*) FROM orders");
            $query->execute() or die ("counting ERROR");
            $result = $query->fetch(PDO::FETCH_NUM);
            return $result[0];
        } catch (PDOException $e) {
            return $e->getNessage();
        }

    }

	public function getData()
    {
        //Получаем данные из таблицы
        $sql = "SELECT * FROM orders
                INNER JOIN items
                    ON items.item_id = orders.item_id
                INNER JOIN workshops
                    ON workshops.workshop_id = orders.workshop_id
                INNER JOIN units
                    ON items.unit_id = units.unit_id";
        $query = $this->_db->prepare($sql);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    public function setData($prop)
    {
        //$date = date('Y-m-d');
        // Запись данных в таблицу
        //TODO: Возможно перенести в родительский класс
        try {
            var_dump($prop);
            
            $query = $this->_db->prepare("
            INSERT INTO orders(workshop_id, item_id, order_quantity, output_date)
                VALUES (:workshop_id, :item_id, :order_quantity, :output_date)
            ");
            $query->bindParam(':workshop_id',   $prop['workshop_id']);
            $query->bindParam(':order_quantity',$prop['order_quantity']);
            $query->bindParam(':item_id',       $prop['item_id']);
            $query->bindParam(':output_date',   $prop['output_date']);
            
            $query->execute() or die (print_r($query->errorInfo()) );
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
    
    public function deleteRow($param)
    {
        $query = $this->_db->prepare("DELETE FROM orders WHERE workshop_id = :workshop_id AND item_id = :item_id AND output_date = :output_date");
        $query->bindParam(':workshop_id',   $param[0]);
        $query->bindParam(':item_id',       $param[1]);
        $query->bindParam(':output_date',   $param[2]);
        
        $query->execute() or die(print_r($query->errorInfo()) );
    }
}
?>