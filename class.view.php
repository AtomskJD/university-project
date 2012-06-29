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
            case 'orders':
                $this->table_class = new Orders();
                $this->selfpath .="?id=orders";
                break;
            case 'units':
                $this->table_class = new Units();
                $this->selfpath .="?id=units";
                break;
            case 'audit':
                $this->table_class = new Audit();
                $this->selfpath .="?id=audit";
                break;
            
            default:
                $this->table_class = new Storages();
                break;
        }
    }
    public function getDelete($tr)
    {
        $arr = explode('|', $tr);
        $this->table_class->deleteRow($arr);
        header('Location:'.$this->selfpath);
        exit();
        
    }
    
    public function viewTitle()      // Интерфейс к getName
    {
        printf("<p>Имя таблицы: %s</p>", $this->table_class->getTitle());
    }
    
    public function viewCount()     // Интенфейс к getCount
    {
        printf("<p>Таблица модержит %d записей</p>", $this->table_class->getCount());
    }
    
    protected function viewHeaders()   // Интерфейс к getTableProp выводим заголовги полей (depricated)
    {   
        $out = "\r\n<tr>";
        foreach ($this->table_class->getHeaders() as $prop) {
            $out .= '<th>'. $prop .'</th>';
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
        if (($_SERVER['REQUEST_METHOD'] != 'POST') and (!isset($_GET['delete']))){
        
        $table  = $this->table_class->getHeaders(get_class($this->table_class));
        $data   = $this->table_class->getData();
        $prop   = $this->table_class->getTableProp();
        $i      = 0;
        
        //var_dump($data);
        foreach($data as $data_arr){            // Разбираем массив данных
            //var_dump($data_arr);
            $i++;
            foreach($prop as $prop_arr){        // Разбираем массив параметров таблицы-класса
                //var_dump($prop_arr);
                if ($prop_arr['show']){         //TODO: возможно проверка на принадлежность списку свойств заменит проверку SHOW(ненужные поля) 
                    $name = $prop_arr['name'];
                    if ($prop_arr['fkey'])
                        $name = $prop_arr['fkey']['fkey_name'];
                        
                        if (!isset($data_arr[$name]))
                            $data_arr[$name] = '---';
                    $table[$i+1][] = $data_arr[$name];  // видимая часть таблицы + заголовки
                }
                if (isset($prop_arr['pkey'])){          // Примари ключи для удаления
                    $name = $prop_arr['name'];
                    try{
                        if (!array_key_exists($name, $data_arr))
                            throw new Exception("Забыл прописать поле <strong>$name</strong> для таблицы ". $this->table_class->getTableName() ." (смотри <strong>class::". get_class($this->table_class) ."->getData ==> SELECT</strong>)<br />\n");
                            
                            $pkeys[$i][] = $data_arr[$name];
                            
                    } catch (Exception $e){
                        echo "!ВНИМАНИЕ!". $e->getMessage();
                    }
                    
                }
            }
            // тут ссылка на удаление в сводной таблице она ненужна
            if (get_class($this->table_class) != 'Audit')   
                $table[$i+1][] = "<a href=$this->selfpath&delete=". implode('|',$pkeys[$i]) .">удалить</a>";   
        }
        //echo $this->viewHeaders();
        $is_headers=0;                                  // Собираем таблицу
        foreach ($table as $tr){
            $is_headers++;
            echo '<tr>';
            $count = 0;
                foreach ($tr as $td){
                    $count++;
                    if ($is_headers == 1)
                        echo "<th>$td</th>"; 
                    else {
                        $class ='';
                        if ($td < 0){
                            $class = "class='minus'";
                        }elseif((get_class($this->table_class) == 'Audit') and ($count == 7) and ($td == 0))
                            $class = "class='itsok'";
                         elseif((get_class($this->table_class) == 'Audit') and ($count == 7)) $class = "class='plus'";
                        echo "<td $class>$td</td>";
                    }
                }
            echo '</tr>';
        }
        
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
            if (isset($_GET['delete'])){
                $this->getDelete($_GET['delete']);
            }
            // echo $prop_num;
            $out = "<form accept-charset='utf8' action='". $this->selfpath ."' method='post'>\n";
            for ($i=0; $i<$prop_num; $i++){
                if (isset($prop[$i]['hide']) or !$prop[$i]['show'] ) continue;
                if (!$prop[$i]['fkey']){
                    $out .= "<p><div class='field_name'>"
                    . $prop[$i]['t_name']
                    .": </div><input type='text' name='". $prop[$i]['name'] ."'></p>\n";
                }
                else {
                    $out .= "<p><div class='field_name'>". $prop[$i]['t_name'] ." : </div>". $this->viewList($prop[$i])
                        . "</p>\n";
                        
                }
        }
        if (get_class($this->table_class) == 'Audit')  
            $out .= "<input type='hidden' value='Добавить запись'>";
        else
            $out .= "<input type='submit' value='Добавить запись'>";
        $out .= "</form>";
        echo $out;
        } 
        else { // ЕСЛИ POST
            for ($i=0; $i < $prop_num; $i++) {
                $name = $prop[$i]['name'];
                if(array_key_exists('hide', $prop[$i])) continue;
                $arr[$name] = trim(htmlspecialchars(strip_tags($_POST[$name])));
            }
            //var_dump($arr);
            $this->table_class->setData($arr);
            header("Location: ".$this->selfpath);
            exit();
        }

    }
}
?>