<?php

namespace lib;

class User
{
    static string $table = 'users';
    public int $id;
    public string $name;
    public string $email;
    public string $phone;

    public static function addUser(string $username, string $email, string $phone): User
    {
        $db = new DB();
        $query = "INSERT INTO " . self::$table . "(name, email, phone)";
        $query .= " VALUES ('" . trim($username) . "','" . trim($email) . "','" . trim($phone) . "')";
        $query .= " RETURNING *;";
        $res = $db->run_query($query, User::class);
        $db->close_conn();
        //        print_r($res);
        //        echo "=========== ^ res";
        return $res[0];
    }

    public static function find(string $username) : User
    {
        $db = new DB();
        $query = "SELECT * FROM ". self::$table;
        $query .= " where name like '".$username."'";
        $query .= " LIMIT 1;";
        $res = $db->run_query($query,self::class);
        if (empty($res)){
            echo "could not found user with this name (";
            return new User();
        }
        return $res[0];
    }


}