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
        $query = "SELECT * FROM ".self::$table;
        $query .= " WHERE room_id = {$room_id} and start_from >= '". (new \DateTime("now"))->format('Y-m-d H:m:s') ."'";
        $query .= " and end_on <= '". (new \DateTime("now"))->format('Y-m-d H:m:s') ."'";
        $query .= " LIMIT 10;";
        //        print_r($query);
        $qres = $db->run_query($query,self::class);
        $db->close_conn();
        return $qres;
    }


}