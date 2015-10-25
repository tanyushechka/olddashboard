<?php
require_once __DIR__ . '/db/Db.php';
$db = new Db();

$arrResult = [];
$arrResult['cols'] = [
    ['label' => 'action', 'type' => 'string'],
    ['label' => 'value', 'type' => 'number']
];

$sql = 'SELECT action , SUM(duration) val FROM activities
        WHERE created_at BETWEEN :start AND :end
        GROUP BY 1 ORDER BY 1';
$values = $db->dbSelect($sql, [':start' => $_GET['start'], ':end' => $_GET['end']]);

foreach ($values as $key => $value) {
    $arrResult['rows'][$key] = ['c' => [0 => ['v' => $value->action], 1 => ['v' => $value->val]]];
}

//$fileName = __DIR__ . '/getDataTestByAction.json';
//if (!file_exists($fileName)) {
//    $res = fopen($fileName, 'a');
//    fclose($res);
//}
//$json = json_encode($arrResult, JSON_UNESCAPED_UNICODE);
//file_put_contents($fileName, $json);

echo json_encode($arrResult, JSON_UNESCAPED_UNICODE);