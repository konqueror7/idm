<?php
namespace Idm;

require_once '../vendor/autoload.php';

require 'autoload.php';

$db = New DataBaseConnect();

$_POST["name"] = 'Чистых Елена';
$_POST["month"] = '09';



if (!$db->connect->error) {

  $table = '';
  $table_rows = '';
  $dataTable = Array();
  $table_head = '';
  if (!isset($_GET['day'])) {
    $query = New MonthSelectQueryData();
    $dataTableMonth = $query->queryToDataBase(Array("name"=>$_POST["name"], "month"=>$_POST["month"]), $db);

    //
    // echo '<pre>';
    // var_dump($dataTable);
    // echo '</pre>';
    // foreach ($dataTable as $tableRow) {
    //   $table_row = '';
    //   foreach ($tableRow as $key => $column) {
    //     if ($key=='Work_shift' && $column != 'Total') {
    //       $table_row .= '<td><a href="?day='.$column.'">'.$column.'</a></td>';
    //     } else {
    //       $table_row .= '<td>'.$column.'</td>';
    //     }
    //   }
    //   $table_rows .= '<tr>'.$table_row.'</tr>';
    // }
    // $table_head = '  <tr>
    // <th>Смена</th>
    // <th>Начало смены</th>
    // <th>Конец смены</th>
    // <th>Заезд</th>
    // <th>Генеральная уборка</th>
    // <th>Текущая уборка</th>
    // <th>Сумма</th>
    // <tr>';
  } else {
    $query = New DaySelectQueryData();
    $dataTableDay = $query->queryToDataBase(Array("name"=>$_POST["name"], "day"=>$_GET["day"]), $db);
    // foreach ($dataTable as $tableRow) {
    //   $table_row = '';
    //   foreach ($tableRow as $key => $column) {
    //     if ($column !='' || !is_null($column)) {
    //       $table_row .= '<td>'.$column.'</td>';
    //     } else {
    //       $table_row .= '<td> Нет данных </td>';
    //     }
    //   }
    //   $table_rows .= '<tr>'.$table_row.'</tr>';
    // }
    // $table_head = '  <tr>
    // <th>Номер (корпус)</th>
    // <th>Тип номера</th>
    // <th>Тип уборки</th>
    // <th>Начало уборки</th>
    // <th>Конец уборки</th>
    // <th>Оплата</th>
    // <tr>';
  }

  // $table = '<table class="datasheet">'
  //           .$table_head
  //           .$table_rows.
  //           '</table>';
}

$db->connect->close();

try {
  $loader = new \Twig\Loader\FilesystemLoader('./templates');
  $twig = new \Twig\Environment($loader, [
      'debug' => true,
      'cache' => false
  ]);
  if (isset($dataTableDay)) {
    echo $twig->render('day.twig', ['day' => $dataTableDay]);
  } elseif (isset($dataTableMonth)) {
    echo $twig->render('month.twig', ['month' => $dataTableMonth]);
  }

} catch (\Exception $e) {
  die ('ERROR: ' . $e->getMessage());
}
