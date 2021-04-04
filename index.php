<?php
namespace Idm;

require 'autoload.php';

$db = New DataBaseConnect();

$_POST["name"] = 'Чистых Елена';
$_POST["month"] = '09';

$path = ltrim($_SERVER['REQUEST_URI'], '/');

if (!empty($path)) {
  if (\preg_match('/([0-9-]{2}\-)([0-9-]{2}\-)([0-9-]{4})$/', $path)) {
    $_POST['day']=$path;
  } else {
    \header('Location: http://' . $_SERVER['HTTP_HOST']);
  }
}


if (!$db->connect->error) {

  $table = '';
  $table_rows = '';
  $dataTable = Array();
  $table_head = '';
  if (!isset($_POST['day'])) {
    $query = New MonthSelectQueryData();
    $dataTable = $query->queryToDataBase(Array("name"=>$_POST["name"], "month"=>$_POST["month"]), $db);
    foreach ($dataTable as $tableRow) {
      $table_row = '';
      foreach ($tableRow as $key => $column) {
        if ($key=='Work_shift' && $column != 'Total') {
          $table_row .= '<td><a href="'.$column.'">'.$column.'</a></td>';
        } else {
          $table_row .= '<td>'.$column.'</td>';
        }
      }
      $table_rows .= '<tr>'.$table_row.'</tr>';
    }
    $table_head = '  <tr>
    <th>Смена</th>
    <th>Начало смены</th>
    <th>Конец смены</th>
    <th>Заезд</th>
    <th>Генеральная уборка</th>
    <th>Текущая уборка</th>
    <th>Сумма</th>
    <tr>';
  } else {
    $query = New DaySelectQueryData();
    $dataTable = $query->queryToDataBase(Array("name"=>$_POST["name"], "day"=>$_POST["day"]), $db);
    foreach ($dataTable as $tableRow) {
      $table_row = '';
      foreach ($tableRow as $key => $column) {
        if ($column !='' || !is_null($column)) {
          $table_row .= '<td>'.$column.'</td>';
        } else {
          $table_row .= '<td> Нет данных </td>';
        }
      }
      $table_rows .= '<tr>'.$table_row.'</tr>';
    }
    $table_head = '  <tr>
    <th>Номер (корпус)</th>
    <th>Тип номера</th>
    <th>Тип уборки</th>
    <th>Начало уборки</th>
    <th>Конец уборки</th>
    <th>Оплата</th>
    <tr>';
  }

  $table = '<table class="datasheet">'
            .$table_head
            .$table_rows.
            '</table>';
}

$db->connect->close();

$html = \file_get_contents("html.php");

$html = \str_replace('{"content"}', $table, $html);

print $html;
