<?php

namespace lib;

class commands
{

    public static function adduserProcedure($handle): void
    {
        echo "write username: ";
        $username = fgets($handle);
        echo "write email( pattern *@*.*): ";
        $email = fgets($handle);
        while (!preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/',trim($email))){
            echo "email not valid write correct one: ";
            $email = fgets($handle);
        }
        echo "write phone (sample +123(12)123-12-12): ";
        $phone = fgets($handle);
        while (!preg_match('/\+[0-9]{3}\([0-9]{2}\)[0-9]{3}-[0-9]{2}-[0-9]{2}/',trim($phone))){
            echo "phone not valid write correct one: ";
            $phone = fgets($handle);
        }
        echo "okay, trying to write to db ...";
        $result = User::addUser($username,$email,$phone);
        echo "\r\n the user with name {$result->name} got id {$result->id}";
    }

}