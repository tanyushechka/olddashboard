<?php
require_once __DIR__ . '/classes/Db.php';
$db = new Db();

$arrResult = [];
$arrResult['cols'] = [
    ['label' => 'action', 'type' => 'string'],
    ['label' => 'value', 'type' => 'number']
];

$sql = 'SELECT a2.action , SUM(a1.duration) val FROM activities a1, actions a2
        WHERE a1.created_at BETWEEN :start AND :end AND a1.action_id = a2.id
        GROUP BY 1 ORDER BY 2 DESC';
$values = $db->dbSelect($sql, [':start' => $_GET['start'], ':end' => $_GET['end']]);

foreach ($values as $key => $value) {
    $arrResult['rows'][$key] = ['c' => [0 => ['v' => $value->action], 1 => ['v' => $value->val]]];
}

echo json_encode($arrResult, JSON_UNESCAPED_UNICODE);