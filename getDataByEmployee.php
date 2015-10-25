<?php
require_once __DIR__ . '/classes/Db.php';

$arrResult = [];
$db = new Db();

$sql = 'SELECT `employee_id`, SUM(`duration`) `val` FROM `activities`
        WHERE `employee_id` <> \'\' AND `created_at` BETWEEN :start AND :end
        GROUP BY 1 ORDER BY 2 DESC';
$values1 = $db->dbSelect($sql, [':start' => $_GET['start'], ':end' => $_GET['end']]);

foreach ($values1 as $value1) {
    $sql = 'SELECT (SELECT CONCAT_WS(\'  \', first_name, last_name) FROM contacts WHERE id = :id) `employee`,
            `action_id`, SUM(`duration`) `val`
            FROM `activities`
            WHERE `employee_id` = :id and `created_at` BETWEEN :start AND :end
            GROUP BY 2 ORDER BY 2, 3';
    $values2 = $db->dbSelect($sql, [':id' => $value1->employee_id, ':start' => $_GET['start'], ':end' => $_GET['end']]);
    foreach ($values2 as $value2) {
        $arrResult[$value2->employee][$value2->action_id] = $value2->val;
    }
}


echo json_encode($arrResult, JSON_UNESCAPED_UNICODE);

