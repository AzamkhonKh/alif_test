<?php
// get arguments
require "autoloader.php";

try {
    $room = new \lib\Room();
    $rooms = $room->find_room('azam');
    print_r($rooms);
}catch (Exception $e){
    echo $e->getMessage()."\r\n";
}