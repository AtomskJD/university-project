<?php
function __autoload($class_name){
	include "class.". $class_name .".php";
}
class View {
    protected $table_class;
    protected $form_class;
    protected $selfpath;

    function __construct($class_name='storages')
    {
        $this->selfpath = $_SERVER['PHP_SELF'];
        switch ($class_name) {
            case 'storages':
                $this->table_class = new Storages();
                $this->selfpath .="?id=storages";                
                break;
            case 'workshops':
                $this->table_class = new Workshops();
                $this->selfpath .="?id=workshops";
                break;
            
            default:
                $this->table_class = new Storages();
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
    protected function viewHeaders()   // Интерфейс к getTableProp выводим заголовги полей
    {   
        //TODO: перенести в абстрактный класс преобразование getTableProp() в getHeaders
        $out = '<tr>';
        foreach ($this->table_class->getTableProp() as $prop) {
            echo '<td>'. $prop['t_name'] .'</td>';
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
            echo $this->selfpath;
        
            if ($_SERVER['REQUEST_METHOD'] != 'POST'){ // ЕСЛИ GET
                // echo $prop_num;
                $out = "<form accept-charset='utf8' action='". $this->selfpath ."' method='POST'>\n";
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
        } 
        else { // ЕСЛИ POST
            for ($i=0; $i < $prop_num; $i++) { 
                $name = $prop[$i]['name'];
                $arr[$i] = $_POST[$name];
            }
            var_dump($arr);
            $this->table_class->setData($arr);
            header("Location: ".$this->selfpath);
        }

    }
}
?>