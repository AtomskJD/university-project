<?php
include "class.TableAccess.php";
class Storages extends TableAccess {

	public function getData()
    {
        //Получаем данные из таблицы
        $sql = "SELECT * FROM ". $this->table_name;
        var_dump($sql);
        foreach($this->_db->query($sql) as $row){
            return $row;
        }

    }
    public function setData($prop)
    {
        // Запись данных в таблицу
        try {
            var_dump($prop);
            $query = $this->_db->prepare("INSERT INTO ". $this->table_name ." VALUES (?, ?)");
            $query->execute($prop);
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}
?>