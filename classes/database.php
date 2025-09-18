<?php
class Database {
    protected $pdo;

    public function __construct() {
        $host = 'localhost';
        $db   = 'student_attendance_system';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function insert($table, $data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':'.implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ($fields) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        foreach($data as $key=>$value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    public function update($table, $data, $where, $params) {
        $set = [];
        foreach($data as $key=>$value) {
            $set[] = "$key = :$key";
        }
        $sql = "UPDATE {$table} SET ".implode(', ', $set)." WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        foreach(array_merge($data, $params) as $key=>$value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    public function delete($table, $where, $params) {
        $sql = "DELETE FROM {$table} WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        foreach($params as $key=>$value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    public function selectAll($table, $where="", $params=[]) {
        $sql = "SELECT * FROM {$table}".($where ? " WHERE $where" : "");
        $stmt = $this->pdo->prepare($sql);
        foreach($params as $key=>$value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function selectOne($table, $where, $params) {
        $sql = "SELECT * FROM {$table} WHERE $where LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        foreach($params as $key=>$value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>