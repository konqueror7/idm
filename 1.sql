-- Отчёт по всем работам, произведенным горничной Чистых Еленой за сентябрь (1 функция) с итоговой суммой за сентябрь одним запросом

SELECT DATE_FORMAT(stat.`start`, '%d-%m-%Y') AS 'Work_shift',
(SELECT DATE_FORMAT(st.`start`, '%H.%i.%s') FROM idm.`statistics` st WHERE st.`work`=0 AND DATE_FORMAT(st.`start`, '%d-%m-%Y')=`Work_shift`) AS 'begin_work_day',
(SELECT DATE_FORMAT(st.`end`, '%H.%i.%s') FROM idm.`statistics` st WHERE st.`work`=0 AND DATE_FORMAT(st.`start`, '%d-%m-%Y')=`Work_shift`) AS 'end_work_day',
(SELECT COUNT(st_a.`work`) FROM idm.`statistics` st_a WHERE st_a.`work`=1 AND DATE_FORMAT(st_a.`start`, '%d-%m-%Y')=`Work_shift`) AS 'check_in',
(SELECT COUNT(st_b.`work`) FROM idm.`statistics` st_b WHERE st_b.`work`=2 AND DATE_FORMAT(st_b.`start`, '%d-%m-%Y')=`Work_shift`) AS 'general',
(SELECT COUNT(st_c.`work`) FROM idm.`statistics` st_c WHERE st_c.`work`=3 AND DATE_FORMAT(st_c.`start`, '%d-%m-%Y')=`Work_shift`) AS 'current',
SUM((SELECT prices.price FROM prices WHERE prices.room_type=rooms.type AND prices.work=stat.work)+IF(stat.bed=1,30,0)+IF(stat.towels=1,10,0)) AS 'Full_Price'
FROM idm.`statistics` stat
INNER JOIN idm.rooms ON (stat.`room`=rooms.`id`)
INNER JOIN idm.works ON (stat.`work`=works.`id`)
INNER JOIN idm.users ON (stat.`staff`=users.`id`)
WHERE users.name='Чистых Елена' AND DATE_FORMAT(stat.`start`, '%m')='09'
GROUP BY `Work_shift`
UNION ALL
SELECT 'Total', ' ', ' ', ' ', ' ',' ',SUM((SELECT prices.price FROM prices WHERE prices.room_type=rooms.type AND prices.work=statistics.work)+IF(statistics.bed=1,30,0)+IF(statistics.towels=1,10,0)) AS 'Full_Price'
FROM idm.statistics
INNER JOIN idm.rooms ON (statistics.room=rooms.id)
INNER JOIN idm.works ON (statistics.work=works.id)
INNER JOIN idm.users ON (statistics.staff=users.id)
WHERE users.name='Чистых Елена' AND DATE_FORMAT(statistics.`start`, '%m')='09'

-- Список всех работ, проделанных Чистых Еленой в выбранный день (2 функция)

(SELECT
concat(rooms.num, " ",(SELECT builds.name FROM builds WHERE builds.id=rooms.build)) AS 'Room',
rooms.type AS 'Type Room',
works.name AS 'Type_cleaning',
DATE_FORMAT(statistics.start, '%H.%i.%s') AS 'Start_clean',
DATE_FORMAT(statistics.end, '%H.%i.%s') AS 'End_clean',
(SELECT prices.price FROM prices WHERE prices.room_type=rooms.type AND prices.work=statistics.work)+IF(statistics.bed=1,30,0)+IF(statistics.towels=1,10,0) AS 'Full_Price'
FROM idm.statistics
INNER JOIN idm.rooms ON (statistics.room=rooms.id)
INNER JOIN idm.works ON (statistics.work=works.id)
INNER JOIN idm.users ON (statistics.staff=users.id)
WHERE users.name='Чистых Елена' AND DATE_FORMAT(statistics.`start`, '%d-%m-%Y')='02-09-2020'
ORDER BY statistics.id ASC)
UNION ALL
(SELECT "Total", "", "", "", "",
SUM((SELECT prices.price FROM prices WHERE prices.room_type=rooms.type AND prices.work=statistics.work)+IF(statistics.bed=1,30,0)+IF(statistics.towels=1,10,0)) AS "Full_Price"
FROM idm.statistics
INNER JOIN idm.rooms ON (statistics.room=rooms.id)
INNER JOIN idm.works ON (statistics.work=works.id)
INNER JOIN idm.users ON (statistics.staff=users.id)
WHERE users.name='Чистых Елена' AND DATE_FORMAT(statistics.`start`, '%d-%m-%Y')='02-09-2020')
