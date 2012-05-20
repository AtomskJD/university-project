<?php
function __autoload($class_name){
	include "class.". $class_name .".php";
}
class View {
    protected $table_class;
    protected $form_class;

    function __construct($class_name)
    {
        switch ($class_name) {
            case 'storages':
                $this->table_class = new Storages();
                // $this->form_class = new StoragesForm();
                break;
            
            default:
                # code...
                break;
        }
    }

    public function viewTitle()      // Интерфейс к getName
    {
        printf("<p>Имя таблицы: %s</p>", $this->table_class->getTitle());
    }
    public function viewCount()     // Интенфейс к getCount
    {
        printf("<p>Таблица модержит %d записей</p>", $this->table_class->getCount());
    }
    protected function viewHeaders()   // Интерфейс к getHeaders
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
            if (isset($_GET['id'])) echo $_GET['id'];

        $prop = $this->table_class->getTableProp();
        $prop_num = count($prop);
    
        if ($_SERVER['REQUEST_METHOD'] != 'POST'){
            // echo $prop_num;
            $out = "<form action='". $_SERVER['PHP_SELF'] ."' method='POST'>\n";
            for ($i=0; $i<$prop_num; $i++){
                if (!$prop[$i]['fkey']){
                    $out .= "<p>"
                    . $prop[$i]['t_name']
                    .": <input type='text' name='". $prop[$i]['name'] ."'></p>\n";
                }
        }
        $out .= "<input type='submit'>";
        $out .= "</form>";
        echo $out;
    } else {
        for ($i=0; $i < $prop_num; $i++) { 
            $name = $prop[$i]['name'];
            $arr[$i] = $_POST[$name];
        }
        var_dump($arr);
        $this->table_class->setData($arr);
    }

    }
}
$view = new View('storages');

?>
<p><a href="?id=storages">HELLO</a></p>
<h1><?php $view->viewTitle() ?></h1>
<h1><?php $view->viewInfo() ?></h1>
<div><?php $view->setDataForm() ?></div>
<table border=1><?php $view->viewData() ?></table>