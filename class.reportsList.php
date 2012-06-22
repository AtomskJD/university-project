<?php
class reportsList extends TableAccess {
    protected $table_name   = "items_has_workshops";
    protected $table_title  = "Список отчетов";
    protected $table_info   = "Отчет создается в этой таблице";
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
                                            't_name'=> 'номер отчета',
                                            'show'  => 1,
                                            'pkey'  => 1,
                                            'fkey'  => 0
                                            ),
                                        array(
                                            'name'  => 'report_date',                    //что идет в таблицу
                                            't_name'=> 'Дата отчета',
                                            'show'  => 1,
                                            'fkey'  => 0
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
            $query = $this->_db->prepare("SELECT COUNT(*) FROM reports_list");
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
        $sql = "SELECT reports_list.workshop_id, workshop_name, report_id, report_date FROM reports_list
                    INNER JOIN workshops
                        ON workshops.workshop_id = reports_list.workshop_id";
        $query = $this->_db->prepare($sql);
        $query->execute() or die(print_r($query->errorInfo()) ) ;
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    public function setData($prop)
    {
        // Запись данных в таблицу
        try {
            var_dump($prop);
            $query = $this->_db->prepare("INSERT INTO reports_list(workshop_id, report_id, report_date) VALUES (:workshop_id, :report_id, :report_date)");
            $query->bindParam(':workshop_id', $prop['workshop_id']);
            $query->bindParam(':report_id', $prop['report_id']);
            $query->bindParam(':report_date', $prop['report_date']);
            
            $query->execute() or die (print_r($query->errorInfo()) );
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
    public function deleteRow($param)
    {
        $query = $this->_db->prepare("DELETE FROM reports_list WHERE report_id = :report_id AND workshop_id = :workshop_id");
        $query->bindParam(':workshop_id', $param[0]);
        $query->bindParam(':report_id', $param[1]);
        
        $query->execute() or die($query->errorInfo());
    }
}
?>