<?php

namespace Inclitoleo\Pgsql\database;

/**
 * @file ModelDataBase.php
 * Library responsible for DB connection control and manipulation
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
     * Returns the database connection and its methods
     * @return object
     */

     protected function Conn()
    {
        $dataconn = new \stdClass();

        $dataconn->driver = INCLITODRIVER;
        $dataconn->host = INCLITOHOST;
        $dataconn->port = INCLITOPORT;
        $dataconn->username = INCLITOUSER;
        $dataconn->password = INCLITOPWD;
        $dataconn->database = INCLITODBNAME;

        return $dataconn;
    }

}

