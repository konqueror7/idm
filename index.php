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
  } else {
    $query = New DaySelectQueryData();
    $dataTableDay = $query->queryToDataBase(Array("name"=>$_POST["name"], "day"=>$_GET["day"]), $db);
  }
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
