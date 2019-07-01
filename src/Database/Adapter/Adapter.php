<?php

namespace RockSolidSoftware\StrixTest\Database\Adapter;

interface Adapter
{

    /**
     * @param string $sql
     * @return array
     */
    public function rawSql(string $sql): array;

    /**
     * @param string $sql
     * @return int
     */
    public function rawExecSql(string $sql): int;

    /**
     * @param string   $table
     * @param array    $columns
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function select(string $table, array $columns = [], int $limit = null, int $offset = null): array;

    /**
     * @param string $table
     * @param array  $values
     * @return int|null
     */
    public function insert(string $table, array $values): ?int;

    /**
     * @param string $table
     * @param int    $id
     * @param array  $values
     * @param string $column
     * @return bool
     */
    public function updateRow(string $table, int $id, array $values, string $column = 'id'): bool;

    /**
     * @param string $table
     * @param int    $id
     * @param string $column
     * @return bool
     */
    public function deleteRow(string $table, int $id, string $column): bool;

    /**
     * @param string $table
     * @param mixed  $value
     * @param string $column
     * @return array|null
     */
    public function find(string $table, $value, string $column = 'id'): ?array;

    /**
     * @return void
     */
    public function startTransaction();

    /**
     * @return void
     */
    public function commitTransaction();

    /**
     * @return void
     */
    public function rollbackTransaction();

}
