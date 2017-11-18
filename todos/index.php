<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = require __DIR__.'/../pdo.php';
require __DIR__.'/Repository.php';
$repository = new Repository($db);

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $todos = parsePutJson();

    foreach ($todos as &$todo) {
        if (isset($todo['id'])) {
            $repository->update($todo);
        } else {
            $todo['id'] = $repository->create($todo);
        }
    }

    $repository->syncDelete($todos);

    http_response_code(204);
    die();
}

header('Content-Type: application/json');

echo json_encode($repository->get());

function parseRequestJson() : array
{
    return json_decode(file_get_contents('php://input'), true);
}
