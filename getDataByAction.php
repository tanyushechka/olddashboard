<?php
require_once __DIR__ . '/classes/Db.php';

$db = new Db();

$sql = 'SELECT action, SUM(duration) val FROM activities
        WHERE created_at BETWEEN :start AND :end
        GROUP BY 1 ORDER BY 1';


$values = $db->dbSelect($sql, [':start' => $_GET['start'], ':end' => $_GET['end']]);

echo json_encode($values, JSON_UNESCAPED_UNICODE);