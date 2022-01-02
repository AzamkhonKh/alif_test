CREATE
    DATABASE alif_test;

create table rooms
(
    id   serial primary key,
    name varchar(255)
);
create table users
(
    id    serial primary key,
    name  varchar(255),
    email varchar(100) unique,
    phone varchar(100) unique
);


create table schedule_room
(
    id         serial primary key,
    start_from timestamp,
    end_on     timestamp,
    room_id    int,
    user_id    int,
    foreign key (room_id) references rooms (id),
    foreign key (user_id) references users (id)

)