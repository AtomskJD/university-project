<?php
class Units extends TableAccess {
    protected $table_name   = "units";
    protected $table_title  = "единицы измерения";
    protected $table_info   = "таблица содержит единицы измерения товаров";
    protected $table_count  = "none";
    protected $table_prop   = array(
                                        array(
                                            'name'  => 'unit_id',
                                            't_name'=> 'Номер',
                                            'fkey'  => 0,
                                            'show'  => 1,
                                            'pkey'  => 1),
                                        array(
                                            'name'  => 'unit_name',
                                            't_name'=> 'Наименование',
                                            'fkey'  => 0,
                                            'show'  => 1)
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
            $query = $this->_db->prepare("SELECT COUNT(*) FROM units");
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
        $sql = "SELECT unit_id, unit_name FROM units";
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
            $query = $this->_db->prepare("INSERT INTO units(unit_id, unit_name) 
            VALUES (:unit_id, :unit_name)");
            
            $query->bindParam(':unit_id', $prop['unit_id']);
            $query->bindParam(':unit_name', $prop['unit_name']);
            
            $query->execute() or die (print_r($query->errorInfo()) . ' <a href="?id=units">НАЗАД</a>');
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
    
    public function deleteRow($param)
    {
        $query = $this->_db->prepare("DELETE FROM units WHERE unit_id = :unit_id");
        $query->bindParam(':unit_id', $param[0]);
        
        $query->execute() or die(print_r($query->errorInfo()) . ' <a href="?id=units">НАЗАД</a>');
    }
}
?>