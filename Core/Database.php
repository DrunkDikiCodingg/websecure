<?php

namespace Core;

use PDO;

class Database
{
    public $connection;
    public $statement;

    public function __construct($config)
    {
        $dbPath = base_path($config['path']);

        $dsn = "sqlite:$dbPath";

        try {
            $this->connection = new PDO($dsn, null, null, [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);
        
        $this->statement->execute($params);
        
        return $this;
    }

    public function setFetchMode($fetchMode)
    {
        $this->statement->setFetchMode($fetchMode);
        return $this;
    }

    public function get()
    {
        return $this->statement->fetchAll();
    }

    public function find()
    {
        return $this->statement->fetch();
    }

    public function findOrFail()
    {
        $result = $this->find();

        if (! $result) {
            abort();
        }

        return $result;
    }

    public function findById($table, $id, $fetchMode = PDO::FETCH_OBJ)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM {$table} WHERE id = :id");
        $this->statement->execute(['id' => $id]);
        $this->statement->setFetchMode($fetchMode);

        return $this->statement->fetch();
    }

    public function getLastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}
