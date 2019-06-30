<?php

namespace RockSolidSoftware\StrixTest\Database\Adapter;

interface Adapter
{

    /**
     * @param string $sql
     * @return array
     */
    public function rawSql($sql);

    /**
     * @param string $sql
     * @return int
     */
    public function rawExecSql($sql);

    /**
     * @param string   $table
     * @param array    $columns
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function select($table, array $columns = [], $limit = null, $offset = null);

    /**
     * @param string $table
     * @param array  $values
     * @return array
     */
    public function insert($table, array $values);

    /**
     * @param string $table
     * @param int    $id
     * @param array  $values
     * @param string $column
     * @return bool
     */
    public function updateRow($table, $id, array $values, $column = 'id');

    /**
     * @param string $table
     * @param int    $id
     * @param string $column
     * @return bool
     */
    public function deleteRow($table, $id, $column);

    /**
     * @param string $table
     * @param mixed  $value
     * @param string $column
     * @return array|null
     */
    public function find($table, $value, $column = 'id');

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
