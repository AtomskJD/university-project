<?php
include "class.TableAccess.php";

class Storages extends TableAccess {
    protected $table_name = "Склады";
    protected $table_count = "none";
    protected $table_headers = array(
        'storage_id'    => 'Номер склада',
        'storage_name'  => 'Название склада'
        );


    public function getInfo()
    {
        return "таблица содержит номера складов и их наименование";
    }
    public function getName()
    {
        return $this->table_name;
    }

    public function getCount()
    {
        try {   
            $query = $this->_db->prepare("SELECT COUNT(*) FROM Storages");
            $query->execute() or die ("ERROR");
            $result = $query->fetch(PDO::FETCH_NUM);
            return $result[0];
        } catch (PDOException $e){
            return $e->getNessage();
        }

    }

    public function getHeaders()
    {
        return $this->table_headers;
    }

	public function getData()
    {
        //Получаем данные из таблицы
        $sql = "SELECT * FROM storages";
        $query = $this->_db->prepare($sql);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }

    public function setData($id, $storage_name)
    {
        // Запись данных в таблицу
        try {
            var_dump($prop);
            $query = $this->_db->prepare("INSERT INTO ". $this->table_name ." VALUES (?, ?)");
            $query->execute(array($id, $storage_name));
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}
?>