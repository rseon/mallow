<?php

namespace Rseon\Mallow;

use Rseon\Mallow\Exceptions\DatabaseException;
use PDO;
use PDOException;
use PDOStatement;

class Database
{
    const ON_ERROR_EXIT = 'exit';
    const ON_ERROR_EXCEPTION = 'exception';
    const ON_ERROR_CONTINUE = 'continue';
    const RAW_QUERY = true;

    private static $_instance;
    protected $pdo;
    protected $config;

    protected $last_query = null;
    protected $last_query_params = [];
    protected $last_error = [];

    /**
     * Connect to the database
     *
     * @param array $config array(host, username, password, dbname [, port [, charset [, on_error] ]])
     *
     * @return Database
     *
     * @throws DatabaseException
     *
     * @example $db = Database::connect([
     *      'host' => 'localhost',
     *      'username' => 'root',
     *      'password' => '',
     *      'dbname' => 'my_db',
     *      'port' => 3306,
     *      'charset' => 'utf8',
     *      'on_error' => ON_ERROR_EXIT | ON_ERROR_EXCEPTION | ON_ERROR_CONTINUE, // default exit
     * ]);
     *
     * Error handling :
     *   ON_ERROR_EXIT : Display error message and stop script execution.
     *   ON_ERROR_EXCEPTION : Throw the Exception (stop script execution).
     *   ON_ERROR_CONTINUE : For debug purpose only, because execution is not stopped.
     *                       Good practice is debugging with methods like getLastError() or
     *                       debug() then stop execution with an `exit` or `die` instruction.
     *
     */
    public static function connect(array $config)
    {
        return new static($config);
    }

    /**
     * Returns instance of connection
     *
     * @return Database
     * @throws DatabaseException
     * @example Database::get()->getAll()
     *
     */
    public static function get()
    {
        if (!(static::$_instance instanceof static)) {
            throw new DatabaseException('Please connect first');
        }

        return static::$_instance;
    }

    /**
     * Set configuration key
     *
     * @param string $key
     * @param $value
     */
    public function setConfig(string $key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * Get configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Allows you to directly execute methods of the PDO class
     *
     * @return PDO
     *
     * @example $this->getDBO()->execute()
     *
     * @link http://php.net/manual/fr/class.pdo.php
     */
    public function getPDO()
    {
        return $this->pdo;
    }


    /**
     * Returns the rows of the $table corresponding to the $conditions, ordered by $sort and if needed only the $limit rows.
     * You can get $only_fields.
     *
     * @param string $table
     * @param array $conditions
     * @param array $sort
     * @param array|int $limit
     * @param array|string $only_fields
     *
     * @return array
     * @throws DatabaseException
     * @example $this->getAll(
     *   'my_table',
     *   [
     *      'active'               => 1,     // WHERE `active` = 1
     *      'id_user >='           => 1,     // AND `id_user` >= 1
     *      'name IS NOT NULL',              // AND `name` IS NOT NULL
     *      'id_client'            => [3,4], // AND `id_client` IN (3,4)
     *      'id_client NOT IN'        => [5,6]  // AND `id_client` NOT IN (5,6)
     *   ],
     *   ['id_user', 'active DESC'],         // ORDER BY `id_user`, `active` DESC
     *   ['active', 'id_user' => 'user']     // SELECT active, id_user as user
     * )
     * @example $limit is an integer : returns the $limit first rows
     *      $this->getAll('my_table', [], [], 10) // LIMIT 10
     * @example $limit is an array [$nb] : returns the $nb first rows
     *      $this->getAll('my_table', [], [], [10]) // LIMIT 10
     * @example $limit is an array [$nb, $offset] : returns the $nb rows from the $offset'th
     *      $this->getAll('my_table', [], [], [10, 20]) // LIMIT 10 OFFSET 20
     * @example $only_fields is a string
     *      $this->getAll('my_table', [], [], null, 'active, id_user as user') // SELECT active, id_user as user
     * @example $only_fields is an array
     *      $this->getAll('my_table', [], [], null, ['active', 'id_user' => 'user']) // SELECT active, id_user as user
     *
     */
    public function getAll(string $table, array $conditions = [], array $sort = [], $limit = null, $only_fields = '*')
    {
        $_fields = '*';
        if (!empty($only_fields)) {
            if (is_array($only_fields)) {
                $_array_fields = [];
                foreach ($only_fields as $k => $v) {
                    if (is_numeric($k)) {
                        $_array_fields[] = $v;
                    } else {
                        $_array_fields[] = "{$k} as {$v}";
                    }
                }
                $only_fields = $_array_fields;

                $_fields = implode(', ', $only_fields);
            } else {
                $_fields = $only_fields;
            }
        }

        $sql = "SELECT {$_fields} FROM `{$table}`";
        $sql .= static::createWhere($conditions);

        if ($sort) {
            $_order = implode(', ', array_map(function ($v) {
                if (strpos($v, ' ') !== false) {
                    list($a, $b) = explode(' ', $v);
                    return "`{$a}` {$b}";
                }
                return "`{$v}`";
            }, $sort));
            $sql .= " ORDER BY {$_order}";
        }

        if ($limit) {
            $limit_nb = null;
            $limit_offset = 0;

            if (is_numeric($limit)) {
                $limit_nb = (int)$limit;
            } elseif (is_array($limit)) {
                if (isset($limit[0])) {
                    $limit_nb = (int)$limit[0];
                }
                if (isset($limit[1])) {
                    $limit_offset = (int)$limit[1];
                }
            }

            if ($limit_nb) {
                $sql .= " LIMIT {$limit_nb} OFFSET {$limit_offset}";
            }
        }

        $this->resetLastQuery();
        $stmt = $this->pdo->prepare($sql);
        $this->bindValues($stmt, $conditions);
        $this->setLastQuery($stmt);

        try {
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->setLastError($stmt->errorInfo());
            if ($this->config['on_error'] === static::ON_ERROR_EXIT) {
                exit('Error : ' . $e->getMessage() . PHP_EOL);
            }
            if ($this->config['on_error'] === static::ON_ERROR_EXCEPTION) {
                throw new DatabaseException($e->getMessage());
            }
        }

        return [];
    }

    /**
     * Returns one row of the $table corresponding to the $conditions
     *
     * @param string $table
     * @param array $conditions
     * @param array|string $only_fields
     *
     * @return array
     * @throws DatabaseException
     *
     * @see Database::getAll()
     */
    public function getRow(string $table, array $conditions = [], $only_fields = null)
    {
        $res = $this->getAll($table, $conditions, [], 1, $only_fields);
        if (isset($res[0])) {
            return $res[0];
        }

        return [];
    }


    /**
     * Get the value of field $column (false if unknown).
     * If $column is an array, returns an array of the values.
     *
     * @example $this->getValue('my_table', 'active', ['id_user' => 1]); // => string "1"
     * @example $this->getValue('my_table', ['id_user','active'], ['id_user' => 1]); // => array ['id_user' => 1, 'active' => 1];
     *
     * @param string $table
     * @param string|array $column
     * @param array $conditions
     *
     * @return array|bool|mixed
     * @throws DatabaseException
     *
     * @see Database::getRow()
     */
    public function getValue(string $table, $column, array $conditions = [])
    {
        $res = $this->getRow($table, $conditions, $column);
        if (!$res) {
            return null;
        }

        if (is_array($column)) {
            $returns = [];
            foreach ($column as $f) {
                if (isset($res[$f])) {
                    $returns[$f] = $res[$f];
                } else {
                    $returns[$f] = null;
                }
            }

            return $returns;
        }

        if (isset($res[$column])) {
            return $res[$column];
        }

        return null;
    }


    /**
     * Returns all rows of the query $sql.
     * For prepared statements you must fill in the array of values $params.
     *
     * @param string $sql
     * @param array $params
     *
     * @return array
     * @throws DatabaseException
     * @example $this->fetchAll('SELECT * FROM user')
     * @example $this->fetchAll('SELECT * FROM user WHERE id_user = :id_user AND active = 1', ['id_user' => 1])
     *
     */
    public function fetchAll(string $sql, array $params = [])
    {
        $this->resetLastQuery();
        $stmt = $this->pdo->prepare($sql);

        $this->bindValues($stmt, $params);

        $this->setLastQuery($stmt);

        try {
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->setLastError($stmt->errorInfo());
            if ($this->config['on_error'] === static::ON_ERROR_EXIT) {
                exit('Error : ' . $e->getMessage() . PHP_EOL);
            }
            if ($this->config['on_error'] === static::ON_ERROR_EXCEPTION) {
                throw new DatabaseException($e->getMessage());
            }
        }

        return [];
    }

    /**
     * Returns one row of the query $sql.
     * For prepared statements you must fill in the array of values $params.
     *
     * @param string $sql
     * @param array $params
     *
     * @return array
     * @throws DatabaseException
     *
     * @see Database::fetchAll()
     */
    public function fetchRow(string $sql, array $params = [])
    {
        $res = $this->fetchAll($sql, $params);
        if (isset($res[0])) {
            return $res[0];
        }

        return [];
    }


    /**
     * Insertion of $data in the $table and returns number of insertions.
     * For multiple insertions, you must fill in an array of associative arrays with all the same keys.
     *
     * @param string $table
     * @param array $data
     *
     * @return int
     * @throws DatabaseException
     * @example $this->insert('my_table', ['name' => 'foo', 'active' => 1])
     * @example $this->insert('my_table', [['id' => 1, 'name' => 'foo'],['id' => 2, 'name' => 'bar']])
     *
     */
    public function insert(string $table, array $data)
    {
        $keys = array_keys($data);

        // Bulk insert
        $bulk = false;
        if (is_numeric($keys[0]) && is_array($data[0])) {
            $bulk = true;

            $base_keys = array_keys(array_values($data)[0]);
            $_keys = implode(', ', array_map(function ($v) {
                return "`{$v}`";
            }, $base_keys));
            $sql = "INSERT IGNORE INTO `{$table}` ({$_keys}) VALUES ";
            $values = [];
            $exec_values = [];
            foreach ($data as $row) {
                $_values = implode(', ', array_map(function ($v) {
                    return '?';
                }, $row));
                $values[] = "({$_values})";
                $exec_values = array_merge($exec_values, array_values($row));
            }
            $sql .= implode(', ', $values);
        } else {
            // Regular insert
            $_keys = implode(', ', array_map(function ($v) {
                return "`{$v}`";
            }, $keys));
            $_values = implode(', ', array_map(function ($v) {
                return ":{$v}";
            }, $keys));
            $sql = "INSERT INTO `{$table}` ({$_keys}) VALUES ({$_values})";
        }

        $this->resetLastQuery();
        $stmt = $this->pdo->prepare($sql);
        $this->setLastQuery($stmt);

        try {
            $this->pdo->beginTransaction();

            // Bulk insert
            if ($bulk) {
                $stmt->execute($exec_values);
            } else {
                // Regular insert
                $this->bindValues($stmt, $data);
                $stmt->execute();
            }

            $id = $this->pdo->lastInsertId();
            $this->pdo->commit();
            return (int)$id;
        } catch (PDOException $e) {
            $this->pdo->rollback();
            $this->setLastError($stmt->errorInfo());
            if ($this->config['on_error'] === static::ON_ERROR_EXIT) {
                exit('Error : ' . $e->getMessage() . PHP_EOL);
            }
            if ($this->config['on_error'] === static::ON_ERROR_EXCEPTION) {
                throw new DatabaseException($e->getMessage());
            }
        }

        return 0;
    }

    /**
     * Update $table with $data corresponding to the $conditions and returns number of updates
     *
     * @param string $table
     * @param array $data
     * @param array $conditions
     *
     * @return int
     * @throws DatabaseException
     * @example $this->update('my_table', ['name' => 'Test'], ['id_user' => 1])
     *
     */
    public function update(string $table, array $data, array $conditions)
    {
        $sql = "UPDATE `{$table}` SET ";
        foreach ($data as $field => $value) {
            $sql .= "`{$field}` = :{$field}, ";
        }
        $sql = substr($sql, 0, -2);

        $sql .= static::createWhere($conditions);

        $this->resetLastQuery();
        $stmt = $this->pdo->prepare($sql);
        $this->setLastQuery($stmt);

        try {
            $this->bindValues($stmt, $data);
            $this->bindValues($stmt, $conditions);

            $this->pdo->beginTransaction();
            $stmt->execute();
            $nb_rows = $stmt->rowCount();
            $this->pdo->commit();
            return (int)$nb_rows;
        } catch (PDOException $e) {
            $this->pdo->rollback();
            $this->setLastError($stmt->errorInfo());
            if ($this->config['on_error'] === static::ON_ERROR_EXIT) {
                exit('Error : ' . $e->getMessage() . PHP_EOL);
            }
            if ($this->config['on_error'] === static::ON_ERROR_EXCEPTION) {
                throw new DatabaseException($e->getMessage());
            }
        }

        return 0;
    }

    /**
     * Delete from $table corresponding to the $conditions and returns number of deletions
     *
     * @param string $table
     * @param array $conditions
     *
     * @return int
     * @throws DatabaseException
     * @example $this->delete('my_table', ['id_user' => 1])
     *
     */
    public function delete(string $table, array $conditions)
    {
        $sql = "DELETE FROM `{$table}`";
        $sql .= static::createWhere($conditions);

        $this->resetLastQuery();
        $stmt = $this->pdo->prepare($sql);
        $this->setLastQuery($stmt);

        try {
            $this->bindValues($stmt, $conditions);

            $this->pdo->beginTransaction();
            $stmt->execute();
            $nb_rows = $stmt->rowCount();
            $this->pdo->commit();
            return (int)$nb_rows;
        } catch (PDOException $e) {
            $this->pdo->rollback();
            $this->setLastError($stmt->errorInfo());
            if ($this->config['on_error'] === static::ON_ERROR_EXIT) {
                exit('Error : ' . $e->getMessage() . PHP_EOL);
            }
            if ($this->config['on_error'] === static::ON_ERROR_EXCEPTION) {
                throw new DatabaseException($e->getMessage());
            }
        }

        return 0;
    }


    /**
     * Call the routine $name with parameters $params
     *
     * @param string $name
     * @param array $params
     *
     * @return array
     * @throws DatabaseException
     * @example $this->routine('My_Routine', ['foo', 'bar'])
     *
     */
    public function routine(string $name, array $params = [])
    {
        $_params = implode(', ', array_fill(0, count($params), '?'));
        $query = "CALL `{$name}`({$_params});";

        $this->resetLastQuery();
        $stmt = $this->pdo->prepare($query);
        $this->setLastQuery($stmt);

        try {
            $i = 0;
            foreach ($params as $p) {
                $stmt->bindValue(++$i, $p, PDO::PARAM_STR);
                $this->setLastQueryParams($i, $p);
            }
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->setLastError($stmt->errorInfo());
            if ($this->config['on_error'] === static::ON_ERROR_EXIT) {
                exit('Error : ' . $e->getMessage() . PHP_EOL);
            }
            if ($this->config['on_error'] === static::ON_ERROR_EXCEPTION) {
                throw new DatabaseException($e->getMessage());
            }
        }

        return [];
    }

    /**
     * Get last PDO error
     *
     * @return array
     */
    public function getLastError()
    {
        return $this->last_error;
    }

    /**
     * Get last query.
     * If $raw_query, query parameters are replaced by their values.
     *
     * @param bool $raw_query
     *
     * @return string
     */
    public function getLastQuery($raw_query = false)
    {
        $query = $this->last_query;
        if ($raw_query === static::RAW_QUERY) {
            return $query;
        }

        foreach ($this->last_query_params as $field => $value) {
            if (is_array($value)) {
                continue;
            }
            $query = str_replace(":{$field}", "'{$value}'", $query);
        }

        if (strpos($query, '?') !== false) {

        }

        return $query;
    }

    /**
     * Get parameters of last prepared query
     *
     * @return array
     */
    public function getLastQueryParams()
    {
        return $this->last_query_params;
    }

    /**
     * Returns an array for debugging with last error, raw query, query with parameters and parameters
     *
     * @return array
     */
    public function debug()
    {
        return [
            'error' => $this->getLastError(),
            'raw_query' => $this->getLastQuery(static::RAW_QUERY),
            'query' => $this->getLastQuery(),
            'query_params' => $this->getLastQueryParams(),
        ];
    }

    /**
     * Database constructor
     *
     * @param array $config
     *
     * @throws DatabaseException
     */
    protected function __construct(array $config)
    {
        $required = ['host', 'dbname', 'username', 'password'];
        foreach ($required as $r) {
            if (!isset($config[$r])) {
                throw new DatabaseException('All configuration fields must be filled : ' . implode(', ', $required));
            }
        }

        if (!array_key_exists('on_error', $config)) {
            $config['on_error'] = static::ON_ERROR_EXIT;
        }

        $this->config = $config;
        $pdoparams = [];
        if (isset($config['charset'])) {
            $pdoparams[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES '{$config['charset']}'";
        }

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
        if (isset($config['port'])) {
            $dsn .= ";port={$config['port']}";
        }

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $pdoparams);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit($e->getMessage() . PHP_EOL);
        }

        static::$_instance = $this;
        return $this;
    }


    /**
     * Returns the type of parameter from value for prepared statement
     *
     * @param mixed $value
     *
     * @return bool|int
     */
    protected static function getParamType($value)
    {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        }
        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        }
        if (is_null($value)) {
            return PDO::PARAM_NULL;
        }
        if (is_string($value)) {
            return PDO::PARAM_STR;
        }
        return false;
    }

    /**
     * Returns an array filled with the field and the operator for a WHERE clause
     *
     * @param string $value
     *
     * @return array
     *
     * @see Database::getAll()
     */
    protected static function splitOperator(string $value)
    {
        $operator = '=';

        if (strpos($value, ' ') !== false) {
            $tmp = explode(' ', $value);
            $value = array_shift($tmp);
            $operator = implode(' ', $tmp);
            unset($tmp);
        }

        return [$value, $operator];
    }

    /**
     * Create the WHERE conditions.
     *
     * @param array $conditions
     *
     * @return string
     */
    protected static function createWhere(array $conditions)
    {
        if (empty($conditions)) {
            return '';
        }

        $where = ' WHERE ';
        foreach ($conditions as $field => $value) {
            $_where = '';
            if (is_array($value)) {
                if (!empty($value)) {
                    list($field, $operator) = static::splitOperator($field);
                    if ($operator === '=') {
                        $operator = 'IN';
                    }
                    $in_values = implode(', ', array_map(function ($v) {
                        return "'{$v}'";
                    }, $value));
                    $_where = "`{$field}` {$operator} ({$in_values})";
                }
            } else {
                if (is_numeric($field)) {
                    list($value, $operator) = static::splitOperator($value);

                    $_where = "`{$value}` {$operator}";
                    unset($conditions[$field]);
                } else {
                    list($field, $operator) = static::splitOperator($field);

                    $_where = "`{$field}` {$operator} :{$field}";
                }
            }

            if ($_where) {
                $where .= "{$_where} AND ";
            }
        }
        $where = substr($where, 0, -5);

        return $where;
    }

    /**
     * Bind the values for prepared queries.
     *
     * @param PDOStatement $stmt
     * @param array $conditions
     */
    protected function bindValues($stmt, array $conditions)
    {
        if (!$conditions) {
            return;
        }

        foreach ($conditions as $field => $value) {
            if (!is_array($value) && !is_numeric($field)) {
                list($field, $operator) = static::splitOperator($field);

                $stmt->bindValue(":{$field}", $value, static::getParamType($value));
                $this->setLastQueryParams($field, $value);
            }
        }
    }

    /**
     * Set last PDO error
     *
     * @param array $errorInfo
     */
    protected function setLastError(array $errorInfo)
    {
        $this->last_error = $errorInfo;
    }

    /**
     * Reset query debugging
     */
    protected function resetLastQuery()
    {
        $this->last_query = null;
        $this->last_query_params = [];
        $this->last_error = [];
    }

    /**
     * Set parameters of last query
     *
     * @param string $field
     * @param mixed $value
     */
    protected function setLastQueryParams(string $field, $value)
    {
        $this->last_query_params[$field] = $value;
    }

    /**
     * Set last query and last error
     *
     * @param PDOStatement $stmt
     */
    protected function setLastQuery($stmt)
    {
        $this->last_query = trim($stmt->queryString);
        $this->last_error = $stmt->errorInfo();
    }
}