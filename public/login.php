<?php

namespace ISYS4283\ToDo;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
session_destroy();

require __DIR__.'/../src/Authenticator.php';
require __DIR__.'/../src/Repository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get post data
    $authenticator = Authenticator::createFromFormRequest();

    // verify login
    Repository::connectMySql(...array_values($authenticator->getCredentials()));

    // give token
    if (isset($_POST['token'])) {
        die($authenticator->getToken());
    }

    // save and redirect to app
    $authenticator->saveToSession();
    header('Location: /');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login TODO</title>
        <link rel="stylesheet" href="https://unpkg.com/todomvc-app-css@2.0.6/index.css">
        <style>
            [v-cloak] { display: none; }
            .input-label {
                padding: 5px;
            }
            .flex-wrapper {
                display: flex;
                justify-content: space-around;
            }
            .flex-wrapper button {
                padding: 25px;
                margin: 10px;
                border: 1px outset #f5f5f5;
                background-color: rgba(175, 47, 47, 0.15);
            }
        </style>
    </head>
    <body>
        <section class="todoapp">
          <header class="header">
            <h1>login</h1>
            <form method="post">
                <label for="username" class="input-label">username</label>
                <input id="username" name="username" class="new-todo"
                  autofocus autocomplete="off"
                  placeholder="username">

                <label for="password" class="input-label">password</label>
                <input id="password" name="password" type="password" class="new-todo"
                  autofocus autocomplete="off"
                  placeholder="password">

                <label for="database" class="input-label">database</label>
                <input id="database" name="database" class="new-todo"
                  autofocus autocomplete="off"
                  placeholder="database">

                <label for="hostname" class="input-label">hostname</label>
                <input id="hostname" name="hostname" class="new-todo"
                  autofocus autocomplete="off"
                  placeholder="hostname" value="localhost">

                <div class="flex-wrapper">
                    <button type="submit">Login</button>
                    <button type="submit" name="token">Get Token</button>
                </div>
            </form>
          </header>
        </section>
    </body>
</html>
