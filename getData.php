<?php
require_once __DIR__ . '/classes/Db.php';


$arrActivities = ['development', 'support', 'marketing', 'documents', 'negotiations'];
$db = new Db();

$sql = 'SELECT c.id, a.action, a.val FROM (
SELECT `contact_id`, `action`, SUM(`duration`) `val` FROM `activities`
        GROUP BY 1, 2 ORDER BY 1, 2) a, contacts c WHERE c.id = a.contact_id AND c.id <> 13';
$values = $db->dbSelect($sql);


$arrResult = [];
foreach ($values as $value) {
    $arrResult[$value->id][$value->action] = $value->val;
}
//
//$arrResult =
//    [
//        'cols' => [
//            ['id' => 'task', 'label' => 'Employee Name', 'type' => 'string'],
//            ['id' => 'endDate', 'label' => 'End Date', 'type' => 'number']
//        ],
//        'rows' => [
//            ['c' => ['v' => 'Mike', 'v' => 600]],
//            ['c' => ['v' => 'Bob', 'v' => 100]],
//            ['c' => ['v' => 'Alice', 'v' => 200]],
//            ['c' => ['v' => 'Frank', 'v' => 300]],
//            ['c' => ['v' => 'Floyd', 'v' => 400]],
//            ['c' => ['v' => 'Fritz', 'v' => 500]]
//        ]
//    ];

echo json_encode($arrResult, JSON_UNESCAPED_UNICODE);

