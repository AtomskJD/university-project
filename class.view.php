<?php
function __autoload($class_name){
	include "class.". $class_name .".php";
}
class View {
    protected $table_class;
    function __construct($class_name)
    {
        switch ($class_name) {
            case 'storages':
                $this->table_class = new Storages();
                break;
            
            default:
                # code...
                break;
        }
    }


	public function getTable()
	{  
        $data = $this->table_class->getData();
        foreach (new TableView(new RecursiveArrayIterator($data)) as $value) {
            echo "<td>$value</td>";
        }
    }
    public function getTableInfo()
    {
        $table_info = $this->table_class->getInfo();
		printf("<h1>Это вывод вида для %s</h1>", $table_info);
    }
    public function setForm()
    {}
    public function getTableCount()
    {
        return $this->table_class->getCount();
    }
}
$view = new View('storages');
// $view->getDataView();
$view->getTableInfo();
echo $view->getTableCount();
?>
<table border=1><?php $view->getTable() ?></table>