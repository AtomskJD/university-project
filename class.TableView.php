<?php //class.TableView.php

class TableView extends RecursiveIteratorIterator {
    function __construct($it)
    {
        parent::__construct($it, self::LEAVES_ONLY);
    }
    function beginChildren()
    {
        echo '<tr>';
    }
    function endChildren()
    {
        echo '</tr>';
    }
}
?>