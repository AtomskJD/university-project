<?php
header("Content-type: text/html; charset=utf-8"); 
    include_once "class.view.php";
    if (isset($_GET['id'])){
        $view = new View($_GET['id']);
    } else {
        $view = new View();
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" version="-//W3C//DTD XHTML 1.1//EN" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>untitled</title>
<meta name="description" content="content" />
<link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
<link rel="Shortcut Icon" href="/favicon.ico" type="image/x-icon" />
</head>
<body>
<div id="header">
 
</div>
<div id="nav">
 <a href="?id=storages">Склады</a>
 <a href="?id=workshops">Цеха</a>
</div>
<div id="main">
 <?php $view->viewTitle(); ?>
 <?php $view->viewInfo(); ?>
 <p><table border=1><?php $view->viewData(); ?></table></p>
 <hr>
 <?php $view->setDataForm(); ?>
</div>
<div id="footer">
 
</div>
</body>
</html>