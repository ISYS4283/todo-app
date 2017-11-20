<?php

session_start();
if (empty($_SESSION['credentials'])) {
    header('Location: /login.php');
    die();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>TODO</title>
        <link rel="stylesheet" href="https://unpkg.com/todomvc-app-css@2.0.6/index.css">
        <script src="https://unpkg.com/vue@latest/dist/vue.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.3.4"></script>
        <style>
            [v-cloak] { display: none; }
            nav a {
                position: absolute;
                top: 0px;
                right: 10%;
                text-decoration: none;
                color: inherit;
                padding: 10px;
            }
            nav a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <nav>
            <a href="/login.php">Logout</a>
        </nav>
        <section class="todoapp">
          <header class="header">
            <h1>todos</h1>
            <input class="new-todo"
              autofocus autocomplete="off"
              placeholder="What needs to be done?"
              v-model="newTodo"
              @keyup.enter="addTodo">
          </header>
          <section class="main" v-show="todos.length" v-cloak>
            <input class="toggle-all" type="checkbox" v-model="allDone">
            <ul class="todo-list">
              <li v-for="todo in filteredTodos"
                class="todo"
                :key="todo.id"
                :class="{ completed: todo.completed, editing: todo == editedTodo }">
                <div class="view">
                  <input class="toggle" type="checkbox" v-model="todo.completed" @change="updateTodo(todo)">
                  <label @dblclick="editTodo(todo)">{{ todo.title }}</label>
                  <button class="destroy" @click="removeTodo(todo)"></button>
                </div>
                <input class="edit" type="text"
                  v-model="todo.title"
                  v-todo-focus="todo == editedTodo"
                  @blur="doneEdit(todo)"
                  @keyup.enter="doneEdit(todo)"
                  @keyup.esc="cancelEdit(todo)">
              </li>
            </ul>
          </section>
          <footer class="footer" v-show="todos.length" v-cloak>
            <span class="todo-count">
              <strong>{{ remaining }}</strong> {{ remaining | pluralize }} left
            </span>
            <ul class="filters">
              <li><a href="#/all" :class="{ selected: visibility == 'all' }">All</a></li>
              <li><a href="#/active" :class="{ selected: visibility == 'active' }">Active</a></li>
              <li><a href="#/completed" :class="{ selected: visibility == 'completed' }">Completed</a></li>
            </ul>
            <button class="clear-completed" @click="removeCompleted" v-show="todos.length > remaining">
              Clear completed
            </button>
          </footer>
        </section>
        <footer class="info">
          <p>Double-click to edit a todo</p>
          <p>Written by <a href="http://evanyou.me">Evan You</a> and <a href="https://jeffpuckett.com">Jeff Puckett</a></p>
          <p>Part of <a href="http://todomvc.com">TodoMVC</a> and <a href="https://isys4283.walton.uark.edu">ISYS4283</a></p>
        </footer>
        <script src="app.js"></script>
    </body>
</html>
