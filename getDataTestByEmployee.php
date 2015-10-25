<?php
require_once __DIR__ . '/classes/Db.php';
$db = new Db();

$arrResult = [];
$arrResult['cols'][0] = ['label' => 'employee', 'type' => 'string'];
$arrResult['rows'] = [];

$sql = 'SELECT action FROM actions ORDER BY 1';
$actions = $db->dbSelect($sql);
foreach ($actions as $action) {
    array_push($arrResult['cols'], ['label' => $action->action, 'type' => 'number']);
}

$sql = 'SELECT a.employee_id, CONCAT_WS(\'  \', c.first_name, c.last_name) employee, SUM(a.duration) val
        FROM activities a, contacts c
        WHERE a.employee_id <> \'\' AND a.employee_id = c.id AND a.created_at BETWEEN :start AND :end
        GROUP BY 1 ORDER BY 3 DESC';
$values1 = $db->dbSelect($sql, [':start' => $_GET['start'], ':end' => $_GET['end']]);

foreach ($values1 as $value1) {
    $arrC = [];
    $arrC[0] = ['v' => $value1->employee];
    $sql = 'SELECT `action_id`, SUM(`duration`) `val`
            FROM `activities`
            WHERE `employee_id` = :id and `created_at` BETWEEN :start AND :end
            GROUP BY 1 ORDER BY 1';
    $values2 = $db->dbSelect($sql, [':id' => $value1->employee_id, ':start' => $_GET['start'], ':end' => $_GET['end']]);
    foreach ($values2 as $value2) {
        array_push($arrC, ['v' => $value2->val]);
    }
    array_push($arrResult['rows'], ['c' => $arrC]);
}

echo json_encode($arrResult, JSON_UNESCAPED_UNICODE);