<?php
    // TST
class myClass {
    const DS = 'dance';
    public $vara;
    public $vara2;
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
var_dump($ref->getproperties());
?>