<?php

namespace ISYS4283\ToDo;

use PDO;

class Repository
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
        $todo = $this->filter($todo, [
            'title',
            'completed',
            'id',
        ]);

        $sql = 'UPDATE todos SET title = :title, completed = :completed WHERE id = :id';

        return $this->db->prepare($sql)->execute($todo);
    }

    public function create(array $todo) : int
    {
        $todo = $this->filter($todo, [
            'title',
            'completed',
        ]);

        $sql = 'INSERT INTO todos (title, completed) VALUES (:title, :completed)';

        if ($this->db->prepare($sql)->execute($todo)) {
            return (int)$this->db->lastInsertId();
        }
    }

    public function delete(int $id) : bool
    {
        $sql = 'DELETE FROM todos WHERE id = ?';

        return $this->db->prepare($sql)->execute([$id]);
    }

    protected function filter(array $todo, array $whitelist) : array
    {
        $todo['completed'] = (int)$todo['completed'];

        return array_intersect_key($todo, array_flip($whitelist));
    }
}
