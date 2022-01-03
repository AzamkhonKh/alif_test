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
        //        print_r($res);
        //        echo "=========== ^ res";
        return $res[0];
    }


}