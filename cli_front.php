<?php
// get arguments
require "autoloader.php";
/*
 * features
 *  - create user and login
 *  - get list of rooms
 *  - check availability of room by given date and time
 *
 * */
$handle = fopen("php://stdin", "r");
$line = "";
do {
    try {
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
            default:{
                if (!empty(trim($line))){
                    echo "repeated: " . $line;
                }
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\r\n";
    }
}
while (true);


