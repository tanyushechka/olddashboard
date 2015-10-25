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
$employees = $db->dbSelect($sql, [':start' => $_GET['start'], ':end' => $_GET['end']]);

foreach ($employees as $employee) {
    $arrC = [];
    $arrC[0] = ['v' => $employee->employee];
    foreach ($actions as $action) {
        $sql = 'SELECT `action`, SUM(`duration`) `val`
            FROM `activities`
            WHERE `employee_id` = :id AND action = :action AND `created_at` BETWEEN :start AND :end
            GROUP BY 1';
        $values = $db->dbSelect($sql, [':id' => $employee->employee_id, ':action' => $action->action,
            ':start' => $_GET['start'], ':end' => $_GET['end']])[0];
        if (!empty($values)) {
            array_push($arrC, ['v' => $values->val]);
        } else {
            array_push($arrC, ['v' => 0]);
        }
    }
    array_push($arrResult['rows'], ['c' => $arrC]);

}

echo json_encode($arrResult, JSON_UNESCAPED_UNICODE);