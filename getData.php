<?php
require_once __DIR__ . '/classes/Db.php';

$arrResult = [];
$sql = 'SELECT `id`, CONCAT(`first_name`, SPACE(1), `last_name`) `name`  FROM `contacts` ORDER BY `id`';
$db = new Db();
$contacts = $db->dbSelect($sql, [':username' => $_GET['user']]);
if (empty($contacts)) {
    $arrResult['result'] = false;
    $arrResult['message'] = 'Ничего не найдено';
} else {
    $arrResult = $contacts;
}
echo json_encode($arrResult, JSON_UNESCAPED_UNICODE);