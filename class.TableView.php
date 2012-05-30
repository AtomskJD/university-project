<?php //class.TableView.php

class TableView extends RecursiveIteratorIterator {
    function __construct($it)
    {
        parent::__construct($it, self::LEAVES_ONLY);
    }
    function beginChildren()
    {
        echo "\r\n<tr>";
    }
    function endChildren()
    {
        echo "\r\n</tr>";
    }
}
?>