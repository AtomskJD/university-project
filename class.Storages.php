<?php
class Storages extends TableAccess {
    protected $table_name   = "storages";
    protected $table_title  = "склады";
    protected $table_info   = "таблица содержит номера складов и их наименование";
    protected $table_count  = "none";
    protected $table_prop   = array(
                                        array(
                                            'name'  => 'storage_id',
                                            't_name'=> 'Номер склада',
                                            'fkey'  => 0,
                                            'pkey'  => 1,
                                            'show'  => 1),
                                        array(
                                            'name'  => 'storage_name',
                                            't_name'=> 'Название склада',
                                            'fkey'  => 0,
                                            'show'  => 1)
                                    );

    public function getCount()
    {
        try {   
            $query = $this->_db->prepare("SELECT COUNT(*) FROM Storages");
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
        $sql = "SELECT *, 1+1 FROM storages";
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
            $query = $this->_db->prepare("INSERT INTO storages VALUES (:storage_id, :storage_name)");
            $query->bindParam(':storage_id', $prop['storage_id']);
            $query->bindParam(':storage_name', $prop['storage_name']);
            
            $query->execute() or die (print_r($query->errorInfo()) );
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
    
    public function deleteRow($param)
    {
        $query = $this->_db->prepare("DELETE FROM storages WHERE storage_id = :storage_id");
        $query->bindParam(':storage_id', $param[0]);
        $query->execute() or die (print_r($query->errorInfo()) );
    }
}
?>