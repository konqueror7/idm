<?php

namespace Idm;

class MonthSelectQueryData extends QueryData
{
  public function queryString($data, $db) {
    $fio = mysqli_real_escape_string($db->connect, $data['name']);
    $month = mysqli_real_escape_string($db->connect, $data['month']);
    $recordString = "(SELECT DATE_FORMAT(stat.`start`, '%d-%m-%Y') AS 'Work_shift',
    (SELECT DATE_FORMAT(st.`start`, '%H.%i.%s') FROM idm.`statistics` st WHERE st.`work`=0 AND DATE_FORMAT(st.`start`, '%d-%m-%Y')=`Work_shift`) AS 'begin_work_day',
    (SELECT DATE_FORMAT(st.`end`, '%H.%i.%s') FROM idm.`statistics` st WHERE st.`work`=0 AND DATE_FORMAT(st.`start`, '%d-%m-%Y')=`Work_shift`) AS 'end_work_day',
    (SELECT COUNT(st_a.`work`) FROM idm.`statistics` st_a WHERE st_a.`work`=1 AND DATE_FORMAT(st_a.`start`, '%d-%m-%Y')=`Work_shift`) AS 'check_in',
    (SELECT COUNT(st_b.`work`) FROM idm.`statistics` st_b WHERE st_b.`work`=2 AND DATE_FORMAT(st_b.`start`, '%d-%m-%Y')=`Work_shift`) AS 'general',
    (SELECT COUNT(st_c.`work`) FROM idm.`statistics` st_c WHERE st_c.`work`=3 AND DATE_FORMAT(st_c.`start`, '%d-%m-%Y')=`Work_shift`) AS 'current',
    SUM((SELECT prices.`price` FROM prices WHERE prices.`room_type`=rooms.`type` AND prices.`work`=stat.`work`)+IF(stat.`bed`=1,30,0)+IF(stat.`towels`=1,10,0)) AS 'Full_Price'
    FROM idm.`statistics` stat
    INNER JOIN idm.`rooms` ON (stat.`room`=rooms.`id`)
    INNER JOIN idm.`works` ON (stat.`work`=works.`id`)
    INNER JOIN idm.`users` ON (stat.`staff`=users.`id`)
    WHERE users.`name`='".$fio."' AND DATE_FORMAT(stat.`start`, '%m')='".$month."'
    GROUP BY `Work_shift`)
    UNION ALL
    (SELECT 'Total', ' ', ' ', ' ', ' ',' ',SUM((SELECT prices.`price` FROM prices WHERE prices.`room_type`=rooms.`type` AND prices.`work`=statistics.`work`)+IF(statistics.`bed`=1,30,0)+IF(statistics.`towels`=1,10,0)) AS 'Full_Price'
    FROM idm.`statistics`
    INNER JOIN idm.rooms ON (statistics.`room`=rooms.`id`)
    INNER JOIN idm.works ON (statistics.`work`=works.`id`)
    INNER JOIN idm.users ON (statistics.`staff`=users.`id`)
    WHERE users.name='".$fio."' AND DATE_FORMAT(statistics.`start`, '%m')='".$month."');";
    $selectQuery = $recordString;
    return $selectQuery;
  }

}
