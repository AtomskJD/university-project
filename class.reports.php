<?php
class Reports extends TableAccess {
    protected $table_name   = "reports";
    protected $table_title  = "Отчеты цехов";
    protected $table_info   = "Отчеты цехов";
    protected $table_count  = "none";
    protected $table_prop   = array(                                       
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
                                            'show'  => 0,
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
        $sql = "SELECT * FROM reports
                INNER JOIN items
                    ON items.item_id = reports.item_id
                INNER JOIN workshops
                    ON workshops.workshop_id = reports.workshop_id
                INNER JOIN units
                    ON items.unit_id = units.unit_id";
        $query = $this->_db->prepare($sql);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    public function setData($prop)
    {
        $date = date('Y-m-d');
        // Запись данных в таблицу
        //TODO: Возможно перенести в родительский класс
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
            
            $query->execute() or die (print_r($query->errorInfo()) );
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}
?>