<?php
require_once __DIR__ . '/classes/Db.php';


$arrActivities = ['development', 'support', 'marketing', 'documents', 'negotiations'];
$db = new Db();

$sql = 'SELECT CONCAT_WS(\'  \', c.first_name, c.last_name) contact, a.action, a.val FROM (
SELECT `contact_id`, `action`, SUM(`duration`) `val` FROM `activities`
        GROUP BY 1, 2 ORDER BY 1, 2) a, contacts c WHERE c.id = a.contact_id';
$values = $db->dbSelect($sql);

$arrResult = [];
foreach ($values as $value) {
    $arrResult[$value->contact][$value->action] = $value->val;
}


echo json_encode($arrResult, JSON_UNESCAPED_UNICODE);

