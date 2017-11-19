<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = require __DIR__.'/../pdo.php';
require __DIR__.'/Repository.php';
$repository = new Repository($db);

echo (new Controller($repository))->sendResponse();

class Controller
{
    protected $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function sendResponse()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        return $this->$method();
    }

    protected function get() : string
    {
        return $this->sendJson($this->repository->get());
    }

    protected function post() : string
    {
        $todo = $this->parseRequestJson();
        $todo['id'] = $this->repository->create($todo);
        return $this->sendJson($todo);
    }

    protected function put() : string
    {
        $todo = $this->parseRequestJson();
        if ($this->repository->update($todo)) {
            return $this->sendJson($todo);
        }
    }

    protected function delete()
    {
        $id = $this->getId();

        if ($id === false) {
            return $this->sendBadRequest();
        }

        if ($this->repository->delete($id)) {
            return $this->sendSuccessNoContent();
        }
    }

    protected function getId()
    {
        if (empty($_GET['id'])) {
            return false;
        }

        return filter_var($_GET['id'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1]
        ]);
    }

    protected function parseRequestJson() : array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    protected function sendJson($data) : string
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }

    protected function sendBadRequest()
    {
        http_response_code(400);
    }

    protected function sendSuccessNoContent()
    {
        http_response_code(204);
    }
}
