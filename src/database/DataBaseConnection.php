<?php

namespace Inclitoleo\Pgsql\database;

/**
 * @file ModelDataBase.php
 * Library responsible for DB connection control and manipulation
 *
 * @name Haush Framework
 * @author LeoCosta (Inclitoleo) <inclitoleo@yandex.com>
 * @copyright Copyright (c) 202022
 * @created 2014-10-29 11:04
 * @revision - 2022-03-11 11:51
 * @version v1.5.2022
 */
class DataBaseConnection
{

    /**
     * string $driver
     */
    private  $driver = 'pgsql';

    /**
     * string $host
     */
    private  $host = 'localhost';

    /**
     * string $username
     */
    private  $username = 'postgres';

    /**
     * string $password
     */
    private  $password = 'Leo@2979';

    /**
     * string $database
     */
    private  $database = 'leonardocosta';

    /**
     * string $port
     */
    private  $port = 5432;
    
    /**
     * Returns the database connection and its methods
     * @return object
     */

     protected function Conn()
    {
        $dataconn = new \stdClass();

        $dataconn->driver = $this->driver;
        $dataconn->host = $this->host;
        $dataconn->port = $this->port;
        $dataconn->username = $this->username;
        $dataconn->password = $this->password;
        $dataconn->database = $this->database;

        return $dataconn;
    }

}

