<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = require __DIR__.'/../pdo.php';
require __DIR__.'/Repository.php';
$repository = new Repository($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $todo = parseRequestJson();
    $todo['id'] = $repository->create($todo);
    sendJson($todo);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    validateId();
    $repository->delete($_GET['id']);
    successNoContent();
}

sendJson($repository->get());

function parseRequestJson() : array
{
    return json_decode(file_get_contents('php://input'), true);
}

function sendJson($data)
{
    header('Content-Type: application/json');
    die(json_encode($data));
}

function badRequest()
{
    http_response_code(400);
    die();
}

function successNoContent()
{
    http_response_code(204);
    die();
}

function validateId()
{
    if (empty($_GET['id'])) {
        badRequest();
    }

    $valid = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ]);

    if ($valid === false) {
        badRequest();
    }
}
