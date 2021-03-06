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
        while (!preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/', trim($email))) {
            echo "email not valid write correct one: ";
            $email = fgets($handle);
        }
        echo "write phone (sample +123(12)123-12-12): ";
        $phone = fgets($handle);
        while (!preg_match('/\+[0-9]{3}\([0-9]{2}\)[0-9]{3}-[0-9]{2}-[0-9]{2}/', trim($phone))) {
            echo "phone not valid write correct one: ";
            $phone = fgets($handle);
        }
        echo "okay, trying to write to db ...";
        $result = User::addUser($username, $email, $phone);
        echo "\r\n the user with name {$result->name} got id {$result->id}";
    }

    public static function loginProcedure($handle): ?User
    {
        echo "write username: ";
        $username = trim(fgets($handle));
        $user = User::find($username);
        if ($username == $user->name) {
            echo "success logged in ! \r\n";
            return $user;
        }
        return null;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function notLoggedInCommands($handle, &$user)
    {

        $line = fgets($handle);

        switch (trim($line)) {
            case "exit":
            {
                echo "exiting ...";
                exit();
                break;
            }
            case "adduser":
            {
                \lib\commands::adduserProcedure($handle);
                break;
            }
            case "login":
            {
                $user = \lib\commands::loginProcedure($handle);
                break;
            }
            case "test":
            {
                $mail = new Mailsender();
                $mail->send("azamkhon.kh@gmail.com","from test alif","hello there boris on call !");
                break;
            }
            default:
            {
                if (!empty(trim($line))) {
                    echo "repeated: " . $line;
                }
            }
        }
    }

    public static function LoggedIn($handle, User $user)
    {
        echo $user->name . ": ";
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
            case "reserveRoom":
            {
                \lib\commands::reserveRoom($handle,$user);
                break;
            }
            case "list":
            {
                self::listRooms();
                break;
            }
        }
    }

    private static function listRooms()
    {
        $rooms = Room::getRooms();
        foreach ($rooms as $room) {
            echo "{$room->id}. {$room->name} \r\n";
        }
    }

    private static function checkRoom($handle, &$_room_id = null)
    {
        echo "let's check room availability there is list of rooms: \r\n";
        self::listRooms();
        echo "input id of room: ";
        $room_ids = Room::getRoomIds();
        $room_id = trim(fgets($handle));
        while (!is_numeric($room_id) && !in_array(intval($room_id), $room_ids)) {
            echo "invalid id of room type again: ";
            $room_id = trim(fgets($handle));
        }
        echo "let's get active schedule of this room for today: \r\n";
        $room_schedule = Schedule::getRoomData(intval($room_id));
        foreach ($room_schedule as $key => $rs) {
            $count = $key + 1;
            echo "{$count}. {$rs->start_from} - {$rs->end_on}\r\n";
        }
        if (!is_null($_room_id)) {
            $_room_id = $room_id;
        }

    }

    private static function reserveRoom($handle, User $user)
    {
        $room_id = 0;
        self::checkRoom($handle, $room_id);
        while (is_null($room_id)) {
            echo "invalid id of room type again: ";
            $room_id = trim(fgets($handle));
        }
        echo "okay, input start of reserve time (pattern 2020-02-01 22:12:00): \r\n";
        $start_time = trim(fgets($handle));

        while (DB::checkDate($start_time)) {
            echo "date is not valid write correct one: ";
            $start_time = trim(fgets($handle));
        }

        echo "Wow, let's write end of reserve time (same pattern 2020-02-01 22:12:00): \r\n";
        $end_time = trim(fgets($handle));

        while (DB::checkDate($end_time)) {
            echo "date is not valid write correct one: ";
            $end_time = trim(fgets($handle));
        }
        echo "okay, trying to write on my pad ... \r\n";
        $schedule = Schedule::setNewSchedule($room_id,$start_time,$end_time,$user);
        if (!is_null($schedule)){
            echo "success ! here inserted result";
            print_r($schedule);
        }
    }

}