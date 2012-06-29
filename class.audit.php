<?php
class Audit extends TableAccess {
    protected $table_name   = "Audit";
    protected $table_title  = "Главная ведомость";
    protected $table_info   = "Помесячно выпускаемая и планируемая продукция";
    protected $table_count  = "none";
    protected $table_prop   = array(                                       
                                        array(
                                            'name'  => 'output_month',                    //что идет в таблицу
                                            't_name'=> 'месяз заявки',
                                            'show'  => 1,
                                            'hide'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => 0
                                            ),
                                        array(
                                            'name'  => 'report_month',                    //что идет в таблицу
                                            't_name'=> 'месяз отчета',
                                            'show'  => 1,
                                            'hide'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => 0
                                            ),
                                        array(
                                            'name'  => 'item_name',                    //что идет в таблицу
                                            't_name'=> 'наименование продукции',
                                            'show'  => 1,
                                            'hide'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => 0
                                            ),
                                        array(
                                            'name'  => 'unit_name',                    //что идет в таблицу
                                            't_name'=> 'Ед. измерения',
                                            'show'  => 1,
                                            'hide'  => 1,
                                            'fkey'  => 0
                                            ),
                                        array(
                                            'name'  => 'order_quantity',                    //что идет в таблицу
                                            't_name'=> 'объем по плану',
                                            'show'  => 1,
                                            'hide'  => 1,
                                            'fkey'  => 0
                                            ),
                                        array(
                                            'name'  => 'summ',                    //что идет в таблицу
                                            't_name'=> 'объем по накладным',
                                            'show'  => 1,
                                            'hide'  => 1,
                                            'fkey'  => 0
                                            ),
                                        array(
                                            'name'  => 'excess',                    //что идет в таблицу
                                            't_name'=> 'излишки',
                                            'show'  => 1,
                                            'hide'  => 1,
                                            'fkey'  => 0
                                            ),
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
            $query = $this->_db->prepare("-- BEGIN MAIN PART
                SELECT COUNT(*) FROM (
                    SELECT * FROM orders
                        
                        LEFT OUTER JOIN (SELECT * FROM order_join) j1 -- use select from view
                            
                            ON orders.item_id=j1.item_id_rep AND MONTH(orders.output_date)=MONTH(j1.report_date)
                        
                    UNION (
                    SELECT * FROM orders
                            
                        RIGHT OUTER JOIN (SELECT * FROM order_join) j2
                        
                            ON orders.item_id=j2.item_id_rep AND MONTH(orders.output_date)=MONTH(j2.report_date)
                        ) 
                ) main1 
                -- END MAIN PANT
                -- just for advanced data NEED MORE JOINs u'mad >:(
                INNER JOIN items
                            ON items.item_id = main1.item_id OR items.item_id = main1.item_id_rep
                            
                LEFT JOIN units
                            ON units.unit_id = items.unit_id
                            
                LEFT JOIN storages
                            ON storages.storage_id = items.storage_id
                            
                LEFT JOIN workshops
                            ON workshops.workshop_id = main1.workshop_id OR workshops.workshop_id = main1.workshop_id_rep
");
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
        $sql = "-- BEGIN MAIN PART
                SELECT workshops.workshop_id, items.item_id, MONTHNAME(output_date) AS output_month, MONTHNAME(report_date) AS report_month, item_name, unit_name, order_quantity, summ, summ-order_quantity AS excess FROM (
                    SELECT * FROM orders
                        
                        LEFT OUTER JOIN (SELECT * FROM order_join) j1 -- use select from view
                            
                            ON orders.item_id=j1.item_id_rep AND MONTH(orders.output_date)=MONTH(j1.report_date)
                        
                    UNION (
                    SELECT * FROM orders
                            
                        RIGHT OUTER JOIN (SELECT * FROM order_join) j2
                        
                            ON orders.item_id=j2.item_id_rep AND MONTH(orders.output_date)=MONTH(j2.report_date)
                        ) 
                ) main1 
                -- END MAIN PANT
                -- just for advanced data NEED MORE JOINs u'mad >:(
                INNER JOIN items
                            ON items.item_id = main1.item_id OR items.item_id = main1.item_id_rep
                            
                LEFT JOIN units
                            ON units.unit_id = items.unit_id
                            
                LEFT JOIN storages
                            ON storages.storage_id = items.storage_id
                            
                LEFT JOIN workshops
                            ON workshops.workshop_id = main1.workshop_id OR workshops.workshop_id = main1.workshop_id_rep
                            
    ORDER BY output_date, report_date, workshops.workshop_id, items.item_id
";
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
            //var_dump($prop);
            
            $query = $this->_db->prepare("
            INSERT INTO reports(report_id, workshop_id, item_id, report_quantity)
                VALUES (:report_id, :workshop_id, :item_id, :report_quantity)
            ");
            $query->bindParam(':report_id', $prop['report_id']);
            $query->bindParam(':workshop_id', $prop['workshop_id']);
            $query->bindParam(':report_quantity', $prop['report_quantity']);
            $query->bindParam(':item_id', $prop['item_id']);
            
            $query->execute() or die (print_r($query->errorInfo()) );
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
    
    public function deleteRow($param)
    {
        $query = $this->_db->prepare("DELETE FROM reports WHERE report_id = :report_id AND workshop_id = :workshop_id AND item_id = :item_id");
        $query->bindParam(':workshop_id', $param[0]);
        $query->bindParam(':report_id', $param[1]);
        $query->bindParam(':item_id', $param[2]);
        
        $query->execute() or die(print_r($query->errorInfo()) );
    }
}
?>