<?php
class Items extends TableAccess {
    protected $table_name   = "items";
    protected $table_title  = "продукция";
    protected $table_info   = "таблица содержит каталог выпускаемой продукции";
    protected $table_count  = "none";
    protected $table_prop   = array(
                                        array(
                                            'name'  => 'item_id',
                                            't_name'=> 'Номер товара',
                                            'fkey'  => 0),
                                        array(
                                            'name'  => 'item_name',
                                            't_name'=> 'Название товара',
                                            'fkey'  => 0),
                                        array(
                                            'name'  => 'storage_id',
                                            't_name'=> 'Название склада',
                                            'fkey'  => 1 
                                            )
                                    );
    public function getForeginKey()
    {
        $query = $this->_db->prepare('SELECT storage_name FROM storages');
        $query->execute() or die ("ERROR gettin FK");
        $out = $query->fetchAll();
        return $out;
    }
    public function getCount()
    {
        try {   
            $query = $this->_db->prepare("SELECT COUNT(*) FROM items");
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
        $sql = "SELECT item_id, item_name, storage_name FROM items INNER JOIN storages
        ON items.storage_id = storages.storage_id";
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
            $query = $this->_db->prepare("INSERT INTO items VALUES (?, ?, ?)");
            $query->execute($prop) or die (print_r($query->errorInfo()) );
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}
?>