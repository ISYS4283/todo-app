<?php

$db = require __DIR__.'/../pdo.php';

$sql = 'SELECT * FROM todos';

$todos = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

echo json_encode($todos);
