<?php
require_once __DIR__ . '/db/Db.php';


$arrActivities = ['development', 'support', 'marketing', 'documents', 'negotiations'];
$currentContactId = 13;
// для каждого контакта делаем следующее:
$quantityActivities = rand(10, 600);
echo $quantityActivities . '        ';
$i = 1;

do {
    $currentAction = rand(0, 4);
    $currentEmployeeId = rand(0, 17);

    $currentCreatedAt = rand(1388520000, 1420059600);
    $hour = (integer)date('G', $currentCreatedAt);
    $dayOfWeek = (integer)date('N', $currentCreatedAt);
    if ($hour < 9 || $hour > 18 || $dayOfWeek == 6 || $dayOfWeek == 7) {

        continue;
    } else {

        echo $i . ' = ' . $hour . ' $dayOfWeek = '.$dayOfWeek. ';   ';
        // определяем сотрудника

        $currentDuration = rand(300, 6000);  // длительность активности в секундах

        // проверяем, была ли уже в этот период времени активность у текущего контакта или текущего сотрудника
        // если уже была, то повторно выбираем $currentTimestamp и повторно проверяем

        $sql = 'SELECT * FROM `activities` WHERE (
            (:currentCreatedAt BETWEEN `created_at` AND (`created_at`+`duration`)) OR
            (:currentCreatedAt < `created_at` AND (:currentCreatedAt + :currentDuration) >= `created_at`))
            AND (`contact_id` IN (:currentContactId, :currentEmployeeId) OR `employee_id` IN (:currentContactId, :currentEmployeeId))';
        $db = new Db();
        $select = $db->dbSelect($sql, [':currentCreatedAt' => $currentCreatedAt, ':currentDuration' => $currentDuration,
            ':currentContactId' => $currentContactId, ':currentEmployeeId' => $currentEmployeeId]);
        if (empty($select)) {
            $sql = 'INSERT INTO `activities` ( `action`,  `created_at`, `duration`, `contact_id`, `employee_id`)
                VALUES (:currentAction, :currentCreatedAt, :currentDuration, :currentContactId, :currentEmployeeId)';
            $insert = $db->dbExecute($sql, [':currentAction' => $currentAction, ':currentCreatedAt' => $currentCreatedAt,
                ':currentDuration' => $currentDuration, ':currentContactId' => $currentContactId,
                ':currentEmployeeId' => $currentEmployeeId]);
            $i++;
        } else {
            continue;
        }


    }
} while ($i <= $quantityActivities);

