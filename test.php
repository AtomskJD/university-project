<?php
    // TST
abstract class abstr {
    public $myVar = "Hello";
    
}
class myClass extends abstr{
    const DS = 'dance';
    public $vara;
    public $vara2;
    static function getArray(){
        return array('aaa'=>'qwe','bbb'=>'asd');
    }
    function __construct()
    {
        $this->vara = 42;
    }
    function getResult()
    {
        echo __METHOD__." :: ".$this->vara;
        echo "<br>". self::DS;
    }
}

$obj = new myClass();
$obj->getResult();
$obj->newprop = 17;
echo $obj->newprop;
$ref = new ReflectionObject($obj);

var_dump($obj->getArray());
//$tempArray = array('aaa', 'bbb');
$tempArray = $obj->getArray();
var_dump($tempArray);

$testArray = array(1=>array('key1'=>'value 1',
                            'key2'=>'vale 2'),
                    2=>array('Key A'=>'value A',
                             'Key B'=>'value B'),
                    3=>array('Key A'=>'value A',
                             'Key B'=>$obj::getArray())
);
var_dump($testArray);
?>
<form method="GET">
<select name="id">
    <option value="715">fkey1</option>
    <option value="720">Fkey2</option>
    <option value="730">Fkey3</option>
    <option value="740">Fkey4</option>
</select>
<input type="submit"/>
</form>