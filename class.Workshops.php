<?php
// include "class.TableAccess.php";

class Workshops extends TableAccess {
    protected $table_name = "workshops";
    protected $table_title = "Цеха";
    protected $table_count = "none";
    protected $table_prop = array(
        array(
            'name'=>'workshop_id',
            't_name'=>'Номер цеха',
            'fkey'=>0),
        array(
            'name'=>'workshop_name',
            't_name'=>'Название цеха',
            'fkey'=>0)
        );

    public function getTableProp()
    {
        return $this->table_prop;
    }

    public function getInfo()
    {
        return "таблица содержит номера и название цехов";
    }
    public function getTitle()
    {
        return $this->table_title;
    }

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
            $query = $this->_db->prepare("INSERT INTO workshops VALUES (?, ?)");
            $query->execute($prop) or die (print_r($query->errorInfo()));
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}
?>