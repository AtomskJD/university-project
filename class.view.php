<?php
function __autoload($class_name){
	include "class.". $class_name .".php";
}
class View {
	function getView(TableAccess $table)
	{
		printf("<h1>Это вывод вида %s</h1>", $table->getInfo());
	}
}
$obj = new Storages();
$view = new View();
$view->getView($obj);
?>