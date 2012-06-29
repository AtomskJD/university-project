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
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
<link rel="Shortcut Icon" href="/favicon.ico" type="image/x-icon" />
</head>
<body>
<div id="header">
 


<div id="nav">
    <span title="список складов"><a href="?id=storages">Склады</a> |</span>
    <span title="список цехов"><a href="?id=workshops">Цеха</a> |</span>
    <span title="список единиц измерения"><a href="?id=units">Ед. измерения</a> |</span>
    <span title="список выпускаемой продукции"><a href="?id=items">Продукция</a> |</span>
    <span title="список выпускаемой цехами продукции"><a href="?id=itemshasworkshops">Продукция - Цеха</a> |</span>
    <span title="список заявок"><a href="?id=orders">ЗАЯВКИ</a> |</span>
    <span title="список накладных"><a href="?id=reportslist">Список накладных</a> |</span>
    <span title="список продукции в накладных"><a href="?id=reports">Содержание накладных</a> |</span>
    <span title="список отчетов цехов"><a href="?id=audit">ОТЧЕТЫ</a></span>
 </div>
</div>
<div id="main">
    <?php $view->viewTitle(); ?>
    <?php $view->viewInfo(); ?>
    
    <p><table><?php $view->viewData(); ?></table></p>
    <hr>
    <?php $view->setDataForm(); ?>
</div>
<div id="footer">
 
</div>
</body>
</html>