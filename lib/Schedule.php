<?php

namespace lib;

use DateTimeInterface;

class Schedule
{
    protected static string $table = 'schedule_room';
    public string $start_from;
    public string $end_on;
    public int $room_id;
    public int $user_id;

    public static function getRoomData(int $room_id): array
    {
        $db = new DB();
        $query = "SELECT * FROM " . self::$table;
        $query .= " WHERE room_id = {$room_id} and start_from <= '" . (new \DateTime("now"))->format('Y-m-d H:m:s') . "'";
        $query .= " and end_on >= '" . (new \DateTime("now"))->format('Y-m-d H:m:s') . "'";
        $query .= " LIMIT 10;";
        //        print_r($query);
        $qres = $db->run_query($query, self::class);
        $db->close_conn();
        return $qres;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function setNewSchedule(string $room_id, string $start_time, string $end_time, User $user)
    {
        if (time() > strtotime($start_time)) {
            echo "Actually we could not reserve from past ( \r\n";
            return null;
        }
        /*
        select * from schedule_room
        where
        id in (
            select id
            from schedule_room
            where room_id = 1
            and start_from >= '2022-01-10 12:00:00'
        )
         and  end_on >= '2022-01-10 15:00:00'*/
        // check is it free space for this time
        $quer = "SELECT * FROM " . self::$table;
        $quer .= " WHERE id in (
            select id 
            from " . self::$table . " 
            where room_id = {$room_id} 
            and start_from >= '{$start_time}'
        )";
        $quer .= " and end_on >= '" . $end_time . ";'";
        $db = new DB();
        $db_res = $db->run_query($quer, self::class);
        if (!empty($db_res)) {
            echo "Agh (( for given time reserved place on time interval: \r\n";
            self::listScheduleData($db_res);
            return null;
        }
        // can be inserted to schedule

        $query = "INSERT INTO " . self::$table . "(start_from, end_on, room_id, user_id)";
        $query .= " VALUES ('" . $start_time . "','" . $end_time . "','" . $room_id . "','" . $user->id . "')";
        $query .= " RETURNING *;";
        $res = $db->run_query($query, self::class);
        $db->close_conn();
        //        print_r($res);
        //        echo "=========== ^ res";

        $mail = new Mailsender();
        $mail->send($user->email, "Room scheduler", "You scheduled room № {$room_id} from {$start_time} till {$end_time}");
        return $res[0];
    }

    // like __toString func
    public static function listScheduleData($data)
    {
        foreach ($data as $key => $rs) {
            $count = $key + 1;
            echo "{$count}. {$rs->start_from} - {$rs->end_on}\r\n";
        }
    }


}