<?php
class Reports extends TableAccess {
    protected $table_name   = "reports";
    protected $table_title  = "Список продукции по накладной цеха";
    protected $table_info   = "Наполнение накладной продукцией цеха";
    protected $table_count  = "none";
    protected $table_prop   = array(
                                        array(
                                            'name'  => 'report_date_month',                    //что идет в таблицу
                                            't_name'=> 'Месяц выпуска',
                                            'show'  => 1,
                                            'hide'  => 1,
                                            'fkey'  => 0 
                                            ),                                       
                                        array(
                                            'name'  => 'workshop_id',                    //что идет в таблицу
                                            't_name'=> 'Название цеха',
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => array(
                                                            'fkey_table'=>'workshops',
                                                            'fkey_name'=>'workshop_name',
                                                            'fkey_id'=>'workshop_id'     //соответствие из ВК
                                                            ) 
                                            ),
                                        array(
                                            'name'  => 'report_id',                    //что идет в таблицу
                                            't_name'=> 'Номер отчета',
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => array(
                                                            'fkey_table' => 'reports_list',
                                                            'fkey_name' => 'report_id',
                                                            'fkey_id' => 'report_id'
                                                            )
                                            ),
                                        array(
                                            'name'  => 'item_id',                    //что идет в таблицу
                                            't_name'=> 'Продукт',
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => array(
                                                            'fkey_table' => 'items',
                                                            'fkey_name' => 'item_name',
                                                            'fkey_id' => 'item_id'
                                                            )
                                            ),
                                         array(
                                            'name'  => 'report_quantity',                    //что идет в таблицу
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
            $query = $this->_db->prepare("SELECT COUNT(*) FROM reports");
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
        $sql = "
        SELECT reports.report_id, reports.workshop_id, reports.item_id, report_quantity, report_date, item_name, workshop_name, items.unit_id, unit_name, MONTHNAME(report_date) AS report_date_month, CONCAT(report_quantity, ' ', unit_name) AS quantity
        FROM reports
                INNER JOIN reports_list
                    ON reports_list.report_id = reports.report_id AND reports_list.workshop_id = reports.workshop_id
                INNER JOIN items
                    ON items.item_id = reports.item_id
                INNER JOIN workshops
                    ON workshops.workshop_id = reports.workshop_id
                INNER JOIN units
                    ON items.unit_id = units.unit_id 
                    
        ORDER BY MONTH(report_date), reports.workshop_id, reports.report_id";
        $query = $this->_db->prepare($sql);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    public function setData($prop)
    {
        try {
            var_dump($prop);
            $query = $this->_db->prepare("
            INSERT INTO reports(report_id, workshop_id, item_id, report_quantity)
                VALUES (:report_id, :workshop_id, :item_id, :report_quantity)
            ");
            $query->bindParam(':report_id', $prop['report_id']);
            $query->bindParam(':workshop_id', $prop['workshop_id']);
            $query->bindParam(':report_quantity', $prop['report_quantity']);
            $query->bindParam(':item_id', $prop['item_id']);
            
            $query->execute() or die (print_r($query->errorInfo()) . ' <a href="?id=reports">НАЗАД</a>');
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
        
        $query->execute() or die(print_r($query->errorInfo()) . ' <a href="?id=reports">НАЗАД</a>');
    }
}
?>