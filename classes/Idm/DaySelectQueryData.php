<?php

namespace Idm;

class DaySelectQueryData extends QueryData
{
  public function queryString($data, $db) {
    $fio = mysqli_real_escape_string($db->connect, $data['name']);
    $day = mysqli_real_escape_string($db->connect, $data['day']);
    $recordString = "(SELECT
    concat(rooms.`num`, ' (',(SELECT builds.name FROM builds WHERE builds.`id`=rooms.`build`),')') AS 'Room',
    rooms.`type` AS 'Type Room',
    works.`name` AS 'Type_cleaning',
    DATE_FORMAT(statistics.`start`, '%H.%i.%s') AS 'Start_clean',
    DATE_FORMAT(statistics.`end`, '%H.%i.%s') AS 'End_clean',
    (SELECT prices.`price` FROM prices WHERE prices.`room_type`=rooms.`type` AND prices.`work`=statistics.`work`)+IF(statistics.`bed`=1,30,0)+IF(statistics.`towels`=1,10,0) AS 'Full_Price'
    FROM idm.statistics
    INNER JOIN idm.`rooms` ON (statistics.`room`=rooms.`id`)
    INNER JOIN idm.`works` ON (statistics.`work`=works.`id`)
    INNER JOIN idm.`users` ON (statistics.`staff`=users.`id`)
    WHERE users.`name`='".$fio."' AND DATE_FORMAT(statistics.`start`, '%d-%m-%Y')='".$day."'
    ORDER BY statistics.`id` ASC)
    UNION ALL
    (SELECT 'Total', '', '', '', '',
    SUM((SELECT prices.`price` FROM prices WHERE prices.`room_type`=rooms.`type` AND prices.work=statistics.`work`)+IF(statistics.`bed`=1,30,0)+IF(statistics.`towels`=1,10,0)) AS 'Full_Price'
    FROM idm.`statistics`
    INNER JOIN idm.`rooms` ON (statistics.room=rooms.`id`)
    INNER JOIN idm.`works` ON (statistics.work=works.`id`)
    INNER JOIN idm.`users` ON (statistics.`staff`=users.`id`)
    WHERE users.`name`='".$fio."' AND DATE_FORMAT(statistics.`start`, '%d-%m-%Y')='".$day."');";
    $selectQuery = $recordString;
    return $selectQuery;
  }


}
