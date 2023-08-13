<?php

namespace Core\Database;

class Db
{
    private \PDO $pdo;

    private static ?self $_instance = null;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new Db();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->pdo = new \PDO(getenv('DSN'), ucfirst(getenv('USER')), getenv('PASSWORD'));
    }

    /**
     * Insert data into database
     * 
     * @param object $model Model parameter
     * @return integer
     */
    public function insert(object $model): int
    {
        try {
            $this->pdo->query('INSERT INTO '. $model->getTable() .' (' . implode(',', array_keys($model->toArray())) . ') VALUES (' . "'" . implode("','", $model->toArray()) . "'" . ')');
        } catch (\Throwable $th) {
            throw $th;
        }
        return $this->pdo->lastInsertId($model->getTable());
    }

    public function update(object $model, int $id): int
    {
        $str = '';
        $i = 0;
        foreach (array_reverse($model->toArray()) as $key => $value) {
            if ($i + 1 === count($model->toArray())
                && $key === 'id'
            ) {
                break;
            }
            if ($i !== 0) {
                $str .= ', ';
            }
            $str .= $key.'='."'" . str_replace("'", "\'",$value) . "'" ;
            $i++;
        }

        $str .= ' WHERE id = '.$id;

        try {
            $this->pdo->query('UPDATE '. $model->getTable() .' SET '.$str);
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->pdo->lastInsertId($model->getTable());
    }

    public function delete(object $model, int $id): bool
    {
        try {
            $this->pdo->query('DELETE FROM '.$model->getTable().' WHERE id = '.$id);
        } catch (\Throwable $th) {
            throw $th;
        }

        return true;
    }

    /**
     * Fetch a data by id
     * 
     * @param string $modelFqcn ModelFqcn parameter
     * @param integer $id Id parameter
     * @return object|bool
     */
    public function fetchOneById(string $modelFqcn, int $id): object | bool
    {
        $query = $this->pdo->query('SELECT * FROM ' . $modelFqcn::getTable() . ' WHERE id = '. $id);
        return $query->fetchObject($modelFqcn);
    }

    /**
     * Fetch one by params
     * 
     * @param string $modelFqcn ModelFqcn parameter
     * @param array $by By parameter
     * @return object|bool
     */
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

    /** 
     * Fetch all data of a model
     * 
     * @param string $modelFqcn ModelFqcn parameter
     * @param string $sortBy SortBy parameter
     * @return []
     */
    public function fetchAll(string $modelFqcn, string $sortBy): array
    {
        $statement = $this->pdo->query('SELECT * FROM ' . $modelFqcn::getTable() . ' ORDER BY ID ' . $sortBy . ';');
        return $statement->fetchAll($this->pdo::FETCH_CLASS, $modelFqcn);
    }

    public function fetchAllWithWhere(string $modelFqcn, string $whereProperty, string $comparator, string $whereValue, string $sortBy): array
    {
        $statement = $this->pdo->query('SELECT * FROM ' . $modelFqcn::getTable() .' WHERE '.$whereProperty.' '.$comparator.' '.'"'.$whereValue.'"'.' ORDER BY ID ' . $sortBy . ';');
        return $statement->fetchAll($this->pdo::FETCH_CLASS, $modelFqcn);
    }
}
