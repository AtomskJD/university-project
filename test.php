<?php
    // TST
class myClass {
    const DS = 'dance';
    public $vara;
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
?>