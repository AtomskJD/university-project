<?php //class.TableView.php

class TableView extends RecursiveIteratorIterator {
    function __construct($it)
    {
        parent::__construct($it, self::LEAVES_ONLY);
        var_dump($it);
    }
    function beginChildren()
    {
        echo "\r\n<tr>";
    }
    function endChildren()
    {
        $curr = $this->key();
        
        echo "\r\n<td>delete$curr</td></tr>";
    }
}
?>