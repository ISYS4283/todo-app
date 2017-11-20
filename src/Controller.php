<?php

namespace ISYS4283\ToDo;

class Controller
{
    protected $repository;

    public function __construct(Repository $repository = null)
    {
        if (isset($repository)) {
            $this->repository = $repository;
        }
    }

    public function sendResponse()
    {
        if (empty($this->repository)) {
            try {
                $credentials = array_values($this->authenticate()->getCredentials());
            } catch (MissingParameter $e) {
                return $this->sendAuthenticationPrompt();
            }

            $this->repository = Repository::connectMySql(...$credentials);
        }

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
        $id = $this->getId();

        if ($id === false) {
            return $this->sendBadRequest();
        }

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

    protected function authenticate() : Authenticator
    {
        try {
            return Authenticator::createFromTokenHeader();
        } catch (MissingToken $e) {}

        try {
            return Authenticator::createFromSession();
        } catch (MissingSessionCredentials $e) {}

        return Authenticator::createFromFormRequest();
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

    protected function sendAuthenticationPrompt()
    {
        http_response_code(401);
    }
}
