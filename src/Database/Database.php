<?php

namespace RockSolidSoftware\StrixTest\Database;

use RockSolidSoftware\StrixTest\Runtime\App;
use RockSolidSoftware\StrixTest\Runtime\Runtime;
use RockSolidSoftware\StrixTest\Database\Adapter\Adapter;

class Database
{

    /** @var Adapter */
    private $adapter;

    /** @var bool */
    private $throwExceptions = true;

    /**
     * Database constructor
     *
     * @throws \Exception
     * @param Adapter|null $adapter
     */
    public function __construct(Adapter $adapter = null)
    {
        if (is_null($adapter)) {
            $adapter = App::make(Runtime::config()->configKey('database.adapter'));
        }

        $this->adapter = $adapter;
    }

    /**
     * @param bool|null $throwExceptions
     * @return bool
     */
    public function throwExceptions(bool $throwExceptions = null): bool
    {
        if (!is_null($throwExceptions)) {
            $this->throwExceptions = $throwExceptions;
        }

        return $this->throwExceptions;
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     * @param string $sql
     * @return array
     */
    public function raw(string $sql): array
    {
        try {
            return $this->adapter->rawSql($sql);
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }

            return [];
        }
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     * @param string $sql
     * @return int
     */
    public function rawExec(string $sql): int
    {
        try {
            return $this->adapter->rawExecSql($sql);
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }

            return 0;
        }
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     * @param string   $table
     * @param array    $columns
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function select(string $table, array $columns = [], int $limit = null, int $offset = null): array
    {
        try {
            return $this->adapter->select($table, $columns, $limit, $offset);
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }

            return [];
        }
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     * @param string   $table
     * @param array    $values
     * @return int|null
     */
    public function insert(string $table, array $values): ?int
    {
        try {
            return $this->adapter->insert($table, $values);
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }

            return null;
        }
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     */
    public function startTransaction()
    {
        try {
            $this->adapter->startTransaction();
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }
        }
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     */
    public function commitTransaction()
    {
        try {
            $this->adapter->commitTransaction();
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }
        }
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     */
    public function rollbackTransaction()
    {
        try {
            $this->adapter->rollbackTransaction();
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }
        }
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     * @param string $table
     * @param int    $id
     * @param array  $values
     * @param string $column
     * @return bool
     */
    public function updateRow(string $table, int $id, array $values, string $column = 'id'): bool
    {
        try {
            return $this->adapter->updateRow($table, $id, $values, $column);
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }

            return false;
        }
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     * @param string $table
     * @param int    $id
     * @param string $column
     * @return bool
     */
    public function deleteRow(string $table, int $id, string $column = 'id'): bool
    {
        try {
            return $this->adapter->deleteRow($table, $id, $column);
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }

            return false;
        }
    }

    /**
     * @throws \Exception
     * @throws \PDOException
     * @param string $table
     * @param mixed  $value
     * @param string $column
     * @return array|null
     */
    public function find(string $table, $value, string $column = 'id'): ?array
    {
        try {
            return $this->adapter->find($table, $value, $column);
        } catch (\Exception $e) {
            if ($this->throwExceptions()) {
                throw $e;
            }

            return null;
        }
    }

}
