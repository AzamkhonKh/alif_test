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
echo "Hello there ! It is cli program you can exit by typing \"exit\" \r\n";
echo "or you can register with \"adduser\" command \r\n";
echo "or you can login with \"login\" command if you already registered. \r\n";
echo "type something here: ";
$handle = fopen("php://stdin", "r");
$line = "";
$user = null;
do {
    try {
        if (is_null($user)){
            \lib\commands::notLoggedInCommands($handle,$user);
        }else{
            \lib\commands::LoggedIn($handle,$user);
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\r\n";
    }
}
while (true);


