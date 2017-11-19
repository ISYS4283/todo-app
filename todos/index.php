<?php

namespace ISYS4283\ToDo;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/Repository.php';
require __DIR__.'/Controller.php';
$db = require __DIR__.'/../pdo.php';

echo (new Controller(new Repository($db)))->sendResponse();
