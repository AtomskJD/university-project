<?php
class Workshops extends TableAccess {
    protected $table_name       = "workshops";
    protected $table_title      = "Цеха";
    protected $table_count      = "none";
    protected $table_info       = "таблица содержит номера и название цехов";
    protected $table_prop = array(
                                    array(
                                        'name'  => 'workshop_id',
                                        't_name'=> 'Номер цеха',
                                        'show'  => 1,
                                        'pkey'  => 1,
                                        'fkey'  => 0),
                                    array(
                                        'name'  => 'workshop_name',
                                        't_name'=> 'Название цеха',
                                        'show'  => 1,
                                        'fkey'  => 0)
                                    );

    public function getCount()
    {
        try {   
            $query = $this->_db->prepare("SELECT COUNT(*) FROM workshops");
            $query->execute() or die ("ERROR");
            $result = $query->fetch(PDO::FETCH_NUM);
            return $result[0];
        } catch (PDOException $e){
            return $e->getMessage();
        }

    }

	public function getData()
    {
        //Получаем данные из таблицы
        $sql = "SELECT * FROM workshops";
        $query = $this->_db->prepare($sql);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    public function setData($prop)
    {
        // Запись данных в таблицу
        try {
            var_dump($prop);
            $query = $this->_db->prepare("INSERT INTO workshops(workshop_id, workshop_name) VALUES (:workshop_id, :workshop_name)");
            $query->bindParam('workshop_id', $prop['workshop_id']);
            $query->bindParam('workshop_name', $prop['workshop_name']);
            
            $query->execute() or die (print_r($query->errorInfo()) . ' <a href="?id=workshops">НАЗАД</a>');
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
    
    public function deleteRow($param)
    {
        $query = $this->_db->prepare("DELETE FROM workshops WHERE workshop_id = :workshop_id");
        $query->bindParam(':workshop_id', $param[0]);
        
        $query->execute() or die(print_r($query->errorInfo()). ' <a href="?id=workshops">НАЗАД</a>');
    }
}
?>