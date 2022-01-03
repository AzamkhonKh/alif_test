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

    public static function loginProcedure($handle): ?User
    {
        echo "write username: ";
        $username = trim(fgets($handle));
        $user = User::find($username);
        if ($username == $user->name){
            echo "success logged in ! \r\n";
            return $user;
        }
        return null;
    }

    public static function notLoggedInCommands($handle,&$user)
    {

        $line = fgets($handle);

        switch (trim($line)){
            case "exit":{
                echo "exiting ...";
                exit();
                break;
            }
            case "adduser":{
                \lib\commands::adduserProcedure($handle);
                break;
            }
            case "login":{
                $user = \lib\commands::loginProcedure($handle);
                break;
            }
            case "test":{
                print_r();
                break;
            }
            default:{
                if (!empty(trim($line))){
                    echo "repeated: " . $line;
                }
            }
        }
    }

    public static function LoggedIn($handle, User $user)
    {
        echo $user->name.": ";
        $line = fgets($handle);

        switch (trim($line)) {
            case "exit":
            {
                echo "exiting ... \r\n";
                exit();
                break;
            }
            case "adduser":
            {
                \lib\commands::adduserProcedure($handle);
                break;
            }
            case "checkRoom":
            {
                \lib\commands::checkRoom($handle);
                break;
            }
            case "list":{
                self::listRooms();
                break;
            }
        }
    }

    private static function listRooms()
    {
        $rooms = Room::getRooms();
        foreach ($rooms as $room){
            echo "{$room->id}. {$room->name} \r\n";
        }
    }

    private static function checkRoom($handle)
    {
        echo "let's check room availability there is list of rooms: \r\n";
        self::listRooms();
        echo "input id of room: ";
        $room_ids = Room::getRoomIds();
        $room_id = trim(fgets($handle));
        while (!is_numeric($room_id) && !in_array(intval($room_id),$room_ids)){
            echo "invalid id of room type again: ";
            $room_id = trim(fgets($handle));
        }
        echo "let's get active schedule of this room for today: \r\n";
        $room_schedule = Schedule::getRoomData(intval($room_id));
        foreach ($room_schedule as $key => $rs){
            $count = $key + 1;
            echo "{$count}. {$rs->start_from} - {$rs->end_on}\r\n";
        }

    }

}