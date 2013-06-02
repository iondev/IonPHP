<?php

class Database
{

    protected static $db = array();
    protected static $mysqli;
    protected static $fetchMode;
    protected static $sql;
    protected static $result;

    /*
     *@name connect
     *@param $db (connection information)
     *@return connection to the MySQL database or error if failed
     *
     */
    public static function connect($db)
    {
        self::$mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['table']);
        if (mysqli_connect_errno()) {
            printf("<b>Connection failed:</b> %s\n", mysqli_connect_error());
            exit;
        }
    }

    /*
     *@name setFetchMode
     *@param $type (Fetch Mode type)
     *@return MYSQLI_NUM / MYSQLI_ASSOC / MYSQLI_BOTH
     *
     */
    public static function setFetchMode($type)
    {
        switch ($type) {
            case 1:
                self::$fetchMode = MYSQLI_NUM;
                break;

            case 2:
                self::$fetchMode = MYSQLI_ASSOC;
                break;

            default:
                self::$fetchMode = MYSQLI_BOTH;
                break;
        }
    }

    /*
     *@name query
     *@param $query (Query string)
     *@return returns query or result
     *
     */

    public static function query($sql)
    {
        self::$sql = self::$mysqli->real_escape_string($sql);
        self::$result = self::$mysqli->query($sql);
        if (self::$result == true) {
            return true;
        } else {
            printf("<b>Problem with SQL:</b> %s\n", self::$sql);
            exit;
        }
    }

    /*
     *@name get
     *@param $field (The field we want from the database query we just used)
     *@return query result from databases selected row
     *
     */

    public static function get($field = null)
    {
        if ($field == null) {
            $data = array();
            while ($row = self::$result->fetch_array(self::$fetchMode)) {
                $data[] = $row;
            }
        } else {
            $row = self::$result->fetch_array(self::$fetchMode);
            $data = $row[$field];
        }
        self::$result->close();
        return $data;
    }

    /*
     *@name id
     *@return the last inserted ID
     *
     */

    public static function id()
    {
        return self::$mysqli->insert_id;
    }

    /*
     *@name __destruct()
     *@return destorys connection to the database
     *
     */

    public function __destruct()
    {
        self::$mysqli->close();
    }
}