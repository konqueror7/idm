<?php
namespace Idm;

require_once '../vendor/autoload.php';

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
    $dataTableMonth = $query->queryToDataBase(Array("name"=>$_POST["name"], "month"=>$_POST["month"]), $db);
  } else {
    $query = New DaySelectQueryData();
    $dataTableDay = $query->queryToDataBase(Array("name"=>$_POST["name"], "day"=>$_POST["day"]), $db);
  }
}

$db->connect->close();

try {
  $loader = new \Twig\Loader\FilesystemLoader('./templates');
  $twig = new \Twig\Environment($loader, [
      'debug' => true,
      'cache' => false
  ]);
  $twig->addExtension(new \Twig\Extension\DebugExtension());

  if (isset($dataTableDay)) {
    echo $twig->render('day.twig', ['day' => $dataTableDay]);
  } elseif (isset($dataTableMonth)) {
    echo $twig->render('month.twig', ['month' => $dataTableMonth]);
  }

} catch (\Exception $e) {
  die ('ERROR: ' . $e->getMessage());
}
