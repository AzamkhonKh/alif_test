<?php

namespace lib;

class Room
{
    public static string $table = 'rooms';
    public int $id;
    public string $name;

    public static function getRooms(): array
    {
        $db = new DB();
        $query = "SELECT * FROM ".self::$table.";";
        $res = $db->run_query($query,self::class);
        $db->close_conn();
        return $res;

    }

    public static function getRoomIds()
    {
        $db = new DB();
        $query = "SELECT * FROM ".self::$table.";";
        $res = $db->run_query($query,self::class);
        $db->close_conn();
        $result = array();
        foreach ($res as $r){
            $result[] = $r->id;
        }
        return $result;
    }

    /**
     * @throws \Exception
     */
    public function initRoom(string $name)
    {
        $this->name = $name;
        $this->find_room($name);
    }

    /**
     * @throws \Exception
     */
    public function find_room($name): Room
    {
        $db = new DB();

        $query = "SELECT * FROM rooms";
        $query .= " where";
        if (is_int($name)){
            $query .= " id =".$name;
        }else{
            $query .= " name like '".$name."'";
        }
        $query .= " LIMIT 1";
        $result = $db->run_query($query,Room::class);
        $db->close_conn();
        if (empty($result)){
            throw new \Exception("Agh problem, We could not find room with this  name ");
        }
        return $result[0];
    }
}