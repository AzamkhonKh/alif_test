<?php

namespace lib;

class Schedule
{
    protected string $table = 'schedule_room';
    public \DateTime $startsFrom;
    public \DateTime $endsOn;
    public int $room_id;
    public int $user_id;


}