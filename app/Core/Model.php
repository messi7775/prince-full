<?php
declare(strict_types=1);

/**
 * Model — base class for all models.
 *
 * Gives every model a shared PDO handle and small query helpers built on
 * prepared statements so SQL injection is impossible by construction.
 */
abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /** Run a prepared statement and return the fetched rows. */
    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Run a prepared statement and return one row (or null). */
    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    /** Run a statement that mutates data; returns the affected row count. */
    protected function execute(string $sql, array $params = []): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /** Insert a row and return its new auto-increment id. */
    protected function insert(string $table, array $data): int
    {
        $cols = array_keys($data);
        $placeholders = array_map(static fn ($c) => ':' . $c, $cols);
        $sql = 'INSERT INTO ' . $table
             . ' (' . implode(', ', $cols) . ')'
             . ' VALUES (' . implode(', ', $placeholders) . ')';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return (int)$this->db->lastInsertId();
    }

    /** Fetch a single scalar (first column of the first row). */
    protected function fetchScalar(string $sql, array $params = []): mixed
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $value = $stmt->fetchColumn();
        return $value === false ? 0 : $value;
    }
}
