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
            case 'items':
                $this->table_class = new Items();
                $this->selfpath .="?id=items";
                break;
                
            case 'itemshasworkshops':
                $this->table_class = new ItemsHasWorkshops();
                $this->selfpath .="?id=itemshasworkshops";
                break;
            case 'reportslist':
                $this->table_class = new reportsList();
                $this->selfpath .="?id=reportslist";
                break;
            case 'reports':
                $this->table_class = new Reports();
                $this->selfpath .="?id=reports";
                break;
            
            default:
                $this->table_class = new Storages();
                break;
        }
    }
    public function deleteRow($tr)
    {
        print_r($tr);
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
        $out = "\r\n<tr>";
        foreach ($this->table_class->getHeaders() as $prop) {
            $out .= '<td>'. $prop .'</td>';
        }
        $out .= "</tr>";
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
        foreach ($data as $tr){
            echo '<tr>';
            foreach ($tr as $td){
                echo "<td>". $td ."</td>";
            }
            echo "<td>";
            foreach ($this->table_class->getPrimaryKey() as $var){
                    echo " ". $var ." = ". $tr[$var] ." ";
                }
            
            echo '</td></tr>';
        }
    }
    public function viewList($fkey)
    {
        $name = $fkey['name'];
        $fkey = $fkey['fkey'];
        $fk = $this->table_class->getForeginKey($fkey);
        
        $out = "<select name='". $name ."'>";
        foreach($fk as $value){
            $out .= "<option value='". $value[$fkey['fkey_id']] ."'>";
            $out .= $value[$fkey['fkey_name']];
            $out .= "</option>";
        }
            $out .= "</select>";
        return $out;
    }
    
    public function setDataForm()   // Интерфейс к setData
    {

        $prop = $this->table_class->getTableProp();
        $prop_num = count($prop);
    
        if ($_SERVER['REQUEST_METHOD'] != 'POST'){ // ЕСЛИ GET
            // echo $prop_num;
            $out = "<form accept-charset='utf8' action='". $this->selfpath ."' method='post'>\n";
            for ($i=0; $i<$prop_num; $i++){
                if (isset($prop[$i]['hide']) ) continue;
                if (!$prop[$i]['fkey']){
                    $out .= "<p>"
                    . $prop[$i]['t_name']
                    .": <input type='text' name='". $prop[$i]['name'] ."'></p>\n";
                }
                else {
                    $out .= "<p>". $prop[$i]['t_name'] ." : ". $this->viewList($prop[$i])
                        . "</p>\n";
                        
                }
        }
        $out .= "<input type='submit'>";
        $out .= "</form>";
        echo $out;
        } 
        else { // ЕСЛИ POST
            for ($i=0; $i < $prop_num; $i++) { 
                $name = $prop[$i]['name'];      // TODO: у нас есть имя поля таблицы но мы его пока не передаем
                $arr[$name] = $_POST[$name];
            }
            var_dump($arr);
            $this->table_class->setData($arr);
            header("Location: ".$this->selfpath);
        }

    }
}
?>