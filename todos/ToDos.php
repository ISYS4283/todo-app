<?php

class ToDos
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function get() : array
    {
        $sql = 'SELECT * FROM todos';

        $todos = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($todos as &$todo) {
            $todo['completed'] = (bool)$todo['completed'];
        }

        return $todos;
    }

    public function update(array $todo) : bool
    {
        $sql = 'UPDATE todos SET title = :title, completed = :completed WHERE id = :id';

        $todo['completed'] = (int)$todo['completed'];

        return $this->db->prepare($sql)->execute($todo);
    }

    public function create(array $todo) : int
    {
        $sql = 'INSERT INTO todos (title, completed) VALUES (:title, :completed)';

        $todo['completed'] = (int)$todo['completed'];

        if ($this->db->prepare($sql)->execute($todo)) {
            return (int)$this->db->lastInsertId();
        }
    }
}
