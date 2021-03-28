<?php

namespace Idm;

abstract class QueryData
{
  abstract function queryString($data, $db);
  public function queryToDataBase($formdata, $db) {
    if ($db->connect->connect_errno) {
      exit();
    } elseif ($db->connect->host_info) {
      $result = $db->connect->query($this->queryString($formdata, $db));
      return $this->resultToArray($result);
    }
  }

  function resultToArray($result) {
    $resultArr = array();
    while (($row = $result->fetch_assoc()) != false) {$resultArr[] = $row;}
    return $resultArr;
  }
}
