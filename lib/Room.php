<?php

namespace lib;

class Room
{

    public int $id;
    public string $name;

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