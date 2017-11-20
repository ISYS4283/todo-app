<?php

namespace ISYS4283\ToDo;

error_reporting(E_ALL);

require __DIR__.'/../src/Repository.php';
require __DIR__.'/../src/Controller.php';
require __DIR__.'/../src/Authenticator.php';

try {
    echo (new Controller)->sendResponse();
} catch (\Throwable $e) {
    http_response_code(500);
    echo 'Exception '.get_class($e)
        .": {$e->getMessage()}\n{$e->getFile()}({$e->getLine()})\n"
        ."{$e->getTraceAsString()}";
}
