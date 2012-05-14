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

    public function viewName()      // Интерфейс к getName
    {
        printf("<p>Имя таблицы: %s</p>", $this->table_class->getName());
    }
    public function viewCount()     // Интенфейс к getCount
    {
        printf("<p>Таблица модержит %d записей</p>", $this->table_class->getCount());
    }
    public function viewHeaders()   // Интерфейс к getHeaders
    {   
        $out = '<tr>';
        foreach ($this->table_class->getHeaders() as $key => $value) {
            $out .= "<td> $value </td>";
        }
        $out .= '</tr>';
        return $out;
    }
    public function viewInfo()      // Интерфейс к getInfo
    {
        $table_info = $this->table_class->getInfo();
        printf("<p>Описание таблицы: %s</p>", $table_info);
    }
    public function viewData()      // Интерфейс к getData
    {  
        $data = $this->table_class->getData();
        echo $this->viewHeaders();
        foreach (new TableView(new RecursiveArrayIterator($data)) as $value) {
            echo "<td>$value</td>";
        }
    }
    public function setDataForm()   // Интерфейс к setData
    {
        $ref = new ReflectionClass($this->table_class);
        print_r( $ref->getMethod('setData')->getNumberOfRequiredParameters());
    }
}
$view = new View('storages');

?>
<h1><?php $view->viewName() ?></h1>
<h1><?php $view->viewHeaders() ?></h1>
<H1><?php $view->setDataForm() ?></H1>
<table border=1><?php $view->viewData() ?></table>