<?php

require_once __DIR__.'/../vendor/autoload.php';

// http://tech.vg.no/2013/07/19/using-phps-built-in-web-server-in-your-test-suites/
// https://github.com/kitetail/zttp/blob/ea4cb888ba6fc46d2bba5b56107afb09030e8131/tests/ZttpTest.php#L389
new class {
    public function __construct()
    {
        $server = getenv('TEST_SERVER_SCRIPT');

        $port = getenv('TEST_SERVER_PORT') ?: 8765;

        $pid = exec("php -S localhost:$port $server > /dev/null 2>&1 & echo $!");

        echo "starting pid $pid test server at localhost:$port\n";

        register_shutdown_function(function () use ($pid, $port) {
            exec("kill $pid");
            echo "killed pid $pid test server at localhost:$port\n";
        });
    }
};
