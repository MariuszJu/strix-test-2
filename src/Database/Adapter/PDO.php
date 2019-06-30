<?php

namespace RockSolidSoftware\StrixTest\Database\Adapter;

use RockSolidSoftware\StrixTest\Runtime\Runtime;

class PDO implements Adapter
{

    /** @var PDO */
    private $pdo;

    /**
     * PDO constructor
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $config = Runtime::config()->configKey('database');

        $this->pdo = new \PDO(sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['name']),
            $config['user'], $config['pass']
        );

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @throws \PDOException
     * @param string $sql
     * @return array
     */
    public function rawSql($sql)
    {
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @throws \PDOException
     * @param string $sql
     * @return int
     */
    public function rawExecSql($sql)
    {
        return $this->pdo->exec($sql);
    }

    /**
     * @throws \PDOException
     * @param string   $table
     * @param array    $columns
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function select($table, array $columns = [], $limit = null, $offset = null)
    {
        if (!empty($columns)) {
            $columns = '`' . implode('`, `', $columns) . '`';
        } else {
            $columns = '*';
        }

        $sql = sprintf('SELECT %s FROM `%s`', $columns, $table);

        if (!is_null($limit)) {
            $sql .= ' LIMIT ' . (int) $limit;
        }

        if (!is_null($offset)) {
            $sql .= ' OFFSET ' . (int) $offset;
        }

        return $this->rawSql($sql);
    }

    /**
     * @throws \PDOException
     * @param string $table
     * @param array  $values
     * @return int
     */
    public function insert($table, array $values)
    {
        $valuesBinds = array_map(function ($key) {
            return ':' . $key;
        }, array_keys($values));

        if (array_key_exists('created_at', $values)) {
            $values['created_at'] = (new \DateTime())->format('Y-m-d H:i:s');
        }

        $sql = vsprintf('INSERT INTO `%s`(%s) VALUES(%s)', [
            $table, '`' . implode('`, `', array_keys($values)) . '`', implode(', ', $valuesBinds),
        ]);

        $stmt = $this->pdo->prepare($sql);

        foreach ($values as $key => $value) {
            $stmt->bindValue(':' . $key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }

        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * @throws \PDOException
     * @param string $table
     * @param mixed  $id
     * @param array  $values
     * @param string $column
     * @return bool
     */
    public function updateRow($table, $id, array $values, $column = 'id')
    {
        $updates = [];

        if (array_key_exists('updated_at', $values)) {
            $values['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');
        }

        foreach ($values as $key => $value) {
            $updates[] = sprintf('`%s` = :%s', $key, $key);
        }

        $sql = vsprintf('UPDATE `%s` SET %s WHERE `%s` = %s', [
            $table, implode(', ', $updates), addslashes($column), addslashes($id),
        ]);

        $stmt = $this->pdo->prepare($sql);

        foreach ($values as $key => $value) {
            $stmt->bindValue(':' . $key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    /**
     * @throws \PDOException
     * @param string $table
     * @param mixed  $id
     * @param string $column
     * @return bool
     */
    public function deleteRow($table, $id, $column = 'id')
    {
        $sql = vsprintf('DELETE FROM `%s` WHERE `%s` = "%s"', [
            $table, addslashes($column), addslashes($id),
        ]);

        return $this->pdo->exec($sql);
    }

    /**
     * @throws \PDOException
     * @param string $table
     * @param mixed  $value
     * @param string $column
     * @return array|null
     */
    public function find($table, $value, $column = 'id')
    {
        $sql = sprintf('SELECT * FROM `%s` WHERE `%s` = :%s', $table, $column, $column);

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':' . $column, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @throws \PDOException
     */
    public function startTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * @throws \PDOException
     */
    public function commitTransaction()
    {
        $this->pdo->commit();
    }

    /**
     * @throws \PDOException
     */
    public function rollbackTransaction()
    {
        $this->pdo->rollBack();
    }

}
