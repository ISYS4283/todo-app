<?php

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

    public function syncDelete(array $todos) : bool
    {
        $ids = $this->getMissingIds($todos);

        $sql = 'DELETE FROM todos WHERE id IN %s';

        $sql = sprintf($sql, $this->getArrayBindPlaceholders($ids));

        return $this->db->prepare($sql)->execute($ids);
    }

    protected function getMissingIds(array $todos) : array
    {
        $ids = [];
        foreach ($todos as $todo) {
            if (isset($todo['id'])) {
                $ids []= $todo['id'];
            }
        }

        $sql = 'SELECT id FROM todos';
        $rows = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            if (!in_array($row['id'], $ids)) {
                $missing []= $row['id'];
            }
        }

        return $missing ?? [];
    }

    protected function getArrayBindPlaceholders(array $variables) : string
    {
        $placeholders = str_repeat('?', count($variables));
        $placeholders = implode(',', str_split($placeholders));
        return "($placeholders)";
    }
}
