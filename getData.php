<?php
require_once __DIR__ . '/classes/Db.php';

$arrResult = [];
$arrActivities = ['development', 'support', 'marketing', 'documents', 'negotiations'];
$db = new Db();

$sql = 'SELECT `contact_id`, SUM(`duration`) `val` FROM `activities`
        WHERE `created_at` BETWEEN :start AND :end
        GROUP BY 1 ORDER BY 2 DESC';
$values1 = $db->dbSelect($sql, [':start' => $_GET['start'], ':end' => $_GET['end']]);

foreach ($values1 as $value1) {
    $sql = 'SELECT (SELECT CONCAT_WS(\'  \', first_name, last_name) FROM contacts WHERE id = :id) `contact`,
            `action`, SUM(`duration`) `val`
            FROM `activities`
            WHERE `contact_id` = :id and `created_at` BETWEEN :start AND :end
            GROUP BY 2 ORDER BY 2, 3';
    $values2 = $db->dbSelect($sql, [':id' => $value1->contact_id, ':start' => $_GET['start'], ':end' => $_GET['end']]);
    foreach ($values2 as $value2) {
        $arrResult[$value2->contact][$value2->action] = $value2->val;
    }
}


//$sql = 'SELECT CONCAT_WS(\'  \', c.first_name, c.last_name) contact, a.action, a.val FROM (
//        SELECT `contact_id`, `action`, SUM(`duration`) `val` FROM `activities`
//        WHERE `created_at` BETWEEN :start AND :end
//        GROUP BY 1, 2 ORDER BY 1, 2) a, contacts c WHERE c.id = a.contact_id';
//$values = $db->dbSelect($sql, [':start' => $_GET['start'], ':end' => $_GET['end']]);


//foreach ($values2 as $value) {
//    $arrResult[$value->contact][$value->action] = $value->val;
//}


echo json_encode($arrResult, JSON_UNESCAPED_UNICODE);

