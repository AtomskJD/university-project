<?php
include "class.TableAccess.php";

class Storages extends TableAccess {
    protected $table_name = "storages";
    protected $table_title = "склады";
    protected $table_count = "none";
    protected $table_headers = array(
        'storage_id'    => 'Номер склада',
        'storage_name'  => 'Название склада'
        );
    protected $table_prop = array(
        array(
            'name'=>'storage_id',
            't_name'=>'Номер склада',
            'fkey'=>0),
        array(
            'name'=>'storage_name',
            't_name'=>'Название склада',
            'fkey'=>0)
        );

    public function getTableProp()
    {
        return $this->table_prop;
    }

    public function getInfo()
    {
        return "таблица содержит номера складов и их наименование";
    }
    public function getTitle()
    {
        return $this->table_title;
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

    public function setData($prop)
    {
        // Запись данных в таблицу
        try {
            var_dump($prop);
            $query = $this->_db->prepare("INSERT INTO storages VALUES (?, ?)");
            $query->execute($prop) or die (print_r($query->errorInfo()));
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}
?>