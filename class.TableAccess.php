<?php
// Абстрактный класс для доступа к таблицам
abstract class TableAccess {
    function getData()
    {
        //Получаем данные из таблицы
    }
    function setData()
    {
        // Запись данных в таблицу
    }
    function getInfo()
    {
        // Получаем имя и описание таблицы
    }
    function __construct()
    {
        // Инициализируем соединение с базой
    }

}
?>