<?php

namespace Core\Database;

use Core\Config;

class Db
{
    private \PDO $pdo;

    private static ?self $_instance = null;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new Db();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $config = Config::getInstance();

        $this->pdo = new \PDO($config->get('dsn'), $config->get('user'), $config->get('password'));
    }

    public function insert(object $model): bool
    {
        $query = $this->pdo->query('INSERT INTO '. $model->getTable() .' (' . implode(',', array_keys($model->toArray())) . ') VALUES (' . "'" . implode("','", $model->toArray()) . "'" . ')');
        return $this->pdo->lastInsertId($model->getTable());
    }

    public function fetchOneById(string $modelFqcn, int $id): object | bool
    {
        $query = $this->pdo->query('SELECT * FROM ' . $modelFqcn::getTable() . ' WHERE id = '. $id);
        return $query->fetchObject($modelFqcn);
    }

    public function fetchOneBy(string $modelFqcn, array $by): object | bool
    {
        $sql = 'SELECT * FROM '. $modelFqcn::getTable() .' WHERE ' . array_keys($by)[0] . ' = ? ';
        for ($i = 1; $i <= count(array_keys($by)); $i++) {
            if (1 === $i) {
                continue;
            }

            $sql .= 'AND ' . array_keys($by)[$i] . ' = ? ';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([implode("','", $by)]);

        return $stmt->fetchObject($modelFqcn);
    }

    public function fetchAll(string $modelFqcn): array
    {
        $statement = $this->pdo->query('SELECT * FROM ' . $modelFqcn::getTable());

        return $statement->fetchAll($this->pdo::FETCH_CLASS, $modelFqcn);
    }
}
