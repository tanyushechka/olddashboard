<?php
require_once __DIR__ . '/classes/Db.php';


$arrActivities = ['development', 'support', 'marketing', 'documents', 'negotiations'];
// для каждого контакта делаем следующее:
$quantityActivities = rand(10, 600);
for ($i = 1; $i <= 1; $i++) {
    $currentActivity = rand(0, 4);
    $currentEmployee = rand(0, 30);
    $currentDuration = rand(300, 6000);
    $currentTimestamp = rand(300, 6000);

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
