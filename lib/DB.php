<?php

namespace lib;

use DateTime;

class DB
{
    public $db_conn = null;
    public array $side_conns;
    public array $config;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $config = require(__DIR__ . '/../config.php');
        if (isset($config['host'], $config['dbname'], $config['dbuser'], $config['dbpassword'])) {
            $this->config = $config;
            $this->start_session();
        } else {
            throw new \Exception("host, dbname, dbuser, dbpassword should be given in config file !");
        }

    }

    public static function checkDate($date)
    {
        $dt = DateTime::createFromFormat("Y-m-d H:m:s", $date);
        return $dt !== false && !array_sum($dt::getLastErrors());
    }

    public function run_query(string $q, $class): array
    {
        $res = array();
        $result = pg_query($q);
        $cursor = 0;
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $temp = new $class();
            foreach ($line as $key => $col_value) {
                $temp->{$key} = $col_value;
            }
            $res[$cursor] = $temp;
            $cursor++;
        }
        pg_free_result($result);
        return $res;
    }

    private function start_session()
    {
        $config = $this->config;
        $host = $config['host'];
        $dbname = $config['dbname'];
        $dbuser = $config['dbuser'];
        $dbpassword = $config['dbpassword'];
        $this->db_conn = pg_connect("host={$host} dbname={$dbname} user={$dbuser} password={$dbpassword}");
    }

    public function close_conn()
    {
        pg_close($this->db_conn);
    }
}