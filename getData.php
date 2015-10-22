<?php
require_once __DIR__ . '/classes/Db.php';


$arrActivities = ['development', 'support', 'marketing', 'documents', 'negotiations'];

$currentContactId = 4;
// для каждого контакта делаем следующее:
$quantityActivities = rand(10, 600);  // определяем количество активностей
for ($i = 1; $i <= $quantityActivities; $i++) {          // для каждой активности делаем следующее:
    $currentAction = rand(0, 4);     // определяем тип активности
    $currentEmployeeId = rand(0, 24);    // определяем сотрудника
    $currentDuration = rand(300, 6000);  // длительность активности в секундах
    $currentCreatedAt = rand(1388520000, 1420059600);  // начала активности - метка юникс-тайм
    // проверяем, была ли уже в этот период времени активность у текущего контакта или текущего сотрудника
    // если уже была, то повторно выбираем $currentTimestamp и повторно проверяем
    $sql = 'SELECT * FROM `activities` WHERE (
            (:currentCreatedAt BETWEEN `created_at` AND (`created_at`+`duration`)) OR
            (:currentCreatedAt < `created_at` AND (:currentCreatedAt + :currentDuration) >= `created_at`))
            AND (`contact_id` IN (:currentContactId, :currentEmployeeId) OR `employee_id` IN (:currentContactId, :currentEmployeeId))';


//echo strtotime('1 January 2015');

$db = new Db();
$select = $db->dbSelect($sql, [':currentCreatedAt' => $currentCreatedAt, ':currentDuration' => $currentDuration,
    ':currentContactId' => $currentContactId, ':currentEmployeeId' => $currentEmployeeId]);
if (empty($select)) {
    $sql = 'INSERT INTO `activities` ( `action`,  `created_at`, `duration`, `contact_id`, `employee_id`)
                VALUES (:currentAction, :currentCreatedAt, :currentDuration, :currentContactId, :currentEmployeeId)';
    $insert = $db->dbExecute($sql, [':currentAction' => $currentAction, ':currentCreatedAt' => $currentCreatedAt,
        ':currentDuration' => $currentDuration, ':currentContactId' => $currentContactId,
        ':currentEmployeeId' => $currentEmployeeId]);
} else {
    continue;
}
}
//$sql = 'SELECT `id`, CONCAT(`first_name`, SPACE(1), `last_name`) `name`  FROM `contacts` ORDER BY `id`';
//$db = new Db();
//$contacts = $db->dbSelect($sql, [':username' => $_GET['user']]);
//if (empty($contacts)) {
//    $arrResult['result'] = false;
//    $arrResult['message'] = 'Ничего не найдено';
//} else {
//    $arrResult = $contacts;
//}
