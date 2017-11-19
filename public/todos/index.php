<?php

namespace ISYS4283\ToDo;

error_reporting(E_ALL);

require __DIR__.'/Repository.php';
require __DIR__.'/Controller.php';
$db = require __DIR__.'/../../pdo.php';

try {
    echo (new Controller(new Repository($db)))->sendResponse();
} catch (\Throwable $e) {
    http_response_code(500);
    echo $e->getMessage();
}
