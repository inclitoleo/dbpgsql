<?php

namespace Inclitoleo\Pgsql\database;

use PDO;

/**
 * Name of the class that contains the database manipulation handling methods.
 * Driver: pgsql Server (PostgreSQL)
 *
 * @name PostgreSqlClient
 * @author @author LeoCosta (Inclitoleo) <inclitoleo@yandex.com>
 * @copyright Copyright (c) 2022
 * @created 2022-02-18 10:12 AM
 * @revision 2022-03-11 11:57 AM
 * @file PostgreSqlClient.php
 * @version v1.0.2022
 *
 */
class PostgreSqlClient extends DataBaseConnection
{
    /**
     * key capsule
     * @var    array
     */
    protected $encapsulateKey = array("`", "`");

    public function __construct()
    {
        try {

            $dns = "{$this->Conn()->driver}:host=" . $this->Conn()->host . ";port=" . $this->Conn()->port . ";dbname=" . $this->Conn()->database;

            $options = array
            (
                PDO::ATTR_PERSISTENT => true
            );

            $this->driver = new PDO($dns, $this->Conn()->username, $this->Conn()->password, $options);

            $this->driver->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Insert data into the database
     *
     * @abstract
     * @param string $table - Get the name of the table.
     * @param object $objValues - Receives the object with the values to be inserted.
     * @return integer last value inserted
     */
    public function insert(string $table, object $objValues): int
    {
        $fields = array();
        $values = array();

        foreach ($objValues as $key => $v) {
            $fields[] = $key;
            $values[] = "?";
        }

        $queryString = "INSERT INTO " . $table . " (" . implode(", ", $fields) . ")
							VALUES (" . implode(", ", $values) . ")";

        try {
            $stmt = $this->driver->prepare($queryString);
            $i = 1;

            foreach ($objValues as $value) {
                $stmt->bindValue($i++, $value);
            }

            $stmt->execute() or die(print_r($stmt->errorInfo()));

            return $this->driver->lastInsertId();

        } catch (PDOException $e) {
            echo 'database - ' . __METHOD__, $e->getMessage() . DIRECTORY_SEPARATOR . $e->getCode();
        }

        return false;
    }


    /**
     * Returns an element inside the object
     *
     * @abstract
     * @param string $table - Name of table in database
     * @param string $field - Database field to be compared default FALSE
     * @param int|float|string $value - Default search element FALSE
     * @return object Returns only one row within an object.
     */
    public function select(string $table, string $field, $value): object
    {
        $condition = NULL;

        if ($field && $value) {
            $condition = " WHERE {$field} = ?";
        }

        $queryString = "SELECT * FROM " . $table . $condition;

        try {
            $stmt = $this->driver->prepare($queryString);

            if (!empty($condition)) {
                $stmt->bindParam(1, $value);
            }

            $stmt->execute() or die(print_r($stmt->errorInfo()));
            return $stmt->fetchObject();

        } catch (PDOException $e) {
            echo $e->getMessage();

        }
        return false;
    }

    /**
     * Returns multiple elements within the object
     *
     * @abstract
     * @param string $table - Name of table in database
     * @param array|bool $sort - Array for sort, array('field','type') type:(ASC or DESC)
     * @return array Returns only one row within an object.
     */
    public function select_s(string $table, $sort = FALSE): array
    {
        $bysort = '';

        if ($sort) {
            $bysort = " ORDER BY " . $sort[0] . " " . $sort[1];
        }

        $queryString = "SELECT * FROM " . $table . $bysort;

        try {
            $stmt = $this->driver->prepare($queryString);

            $stmt->execute() or die(print_r($stmt->errorInfo()));

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            echo 'database - ' . __METHOD__, $e->getMessage() . DIRECTORY_SEPARATOR . $e->getCode();
        }

        return false;
    }

    /**
     * Returns one or several elements within the object (via entire query)
     *
     * @abstract
     * @param string $sqlSelect - SQL Statement
     * @param string $type - Whether to return one or all lines [U = Unique / A = All] (Default -> U)
     * @return array Array of returned data
     */
    public function select_all(string $sqlSelect, string $type = "U"): array
    {
        try {
            $stmt = $this->driver->prepare($sqlSelect);

            $stmt->execute() or die(print_r($stmt->errorInfo()));

            if (strtoupper($type) == "U") {
                return $stmt->fetchObject();
            } elseif (strtoupper($type) == "A") {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }


        } catch (PDOException $e) {
            return $e->getMessage() . DIRECTORY_SEPARATOR . $e->getCode();
        }

        return false;
    }

    /**
     * Updates data in the database of a given element
     *
     * @abstract
     * @param string $table - Database table
     * @param object $objValues Object with values to update
     * (
     *      "field_1->new_value_1",
     *      "field_2->new_value_2",
     *      [...]
     * )
     * @param string $field - Field used as criteria
     * @param string $value - Value used as criterion
     * @return boolean true on success or false otherwise
     */
    public function update(string $table, object $objValues, string $field, string $value): bool
    {
        $fields = array();
        $values = array();

        foreach ($objValues as $key => $_value) {
            $fields[] = $key . " = ?";
            $values[] = $_value;
        }

        $values[] = $value;

        $queryString = "UPDATE " . $table . " SET " . implode(", ", $fields) . " WHERE " . $field . " = ?";

        try {
            $stmt = $this->driver->prepare($queryString);

            for ($i = 1; $i <= count($values); $i++) {
                $stmt->bindValue($i, $values[$i - 1]);
            }

            $stmt->execute() or die(print_r($stmt->errorInfo()));

            return true;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Removes data from the database of a given element
     *
     * @abstract
     * @param string $table - Database table
     * @param string $field - Field used as criteria
     * @param int|float|string $value - Value used as criteria
     * @return boolean true on success or false otherwise
     */
    public function delete(string $table, string $field, $value): bool
    {
        try {

            $queryString = "DELETE FROM " . $table . " WHERE {$field} = ?";

            $stmt = $this->driver->prepare($queryString);
            $stmt->bindValue(1, $value);

            $stmt->execute() or die($stmt->errorInfo());

            return $stmt->rowCount();

        } catch (PDOException $e) {
            echo 'database - ' . __METHOD__, $e->getMessage() . DIRECTORY_SEPARATOR . $e->getCode();
        }

        return false;
    }

    /**
     * @abstract
     * Initiate an SLQ transaction in case of error perform rollback
     * @param string $sql
     * @return array
     *
     */
    public function execute(string $sql): array
    {
        try {

            $this->driver->beginTransaction();
            $stmt = $this->driver->prepare($sql);
            $stmt->execute();
            $this->driver->commit();

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            $this->driver->rollBack();
            echo 'database - ' . __METHOD__, $e->getMessage() . DIRECTORY_SEPARATOR . $e->getCode();
        }
    }


    /**
     * @return array
     */
    public function getEncapsulateKey()
    {
        return $this->encapsulateKey;
    }

    /**
     * @param array $encapsulateKey
     */
    public function setEncapsulateKey($encapsulateKey)
    {
        $this->encapsulateKey = $encapsulateKey;
    }

    /**
     * Protected: Doubles the single quotes (') in the input string
     *
     * @param string $string - String to take effect
     * @return string String change
     */
    protected function escapeString(string $string)
    {
        return preg_replace("/'/is", "''", $string);
    }

}