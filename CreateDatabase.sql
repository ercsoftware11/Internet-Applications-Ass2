drop database if exists adoption;
create database adoption;
grant SELECT, INSERT, UPDATE, DELETE ON adoption.* TO 'webauth'@'localhost';
use adoption;
create table animal
(animalid int unsigned not null auto_increment primary key,
  name varchar(100) not null,
  animal_type varchar(50) not null,
  adoption_fee int not null,
  sex varchar(6) not null,
  desexed boolean not null
);
create table authorized_users
(username varchar(20) not null primary key,
  password varchar(40)
);
insert into authorized_users values
  ('sam', sha1('password'));
insert into animal values
  (1, 'Storm', 'Dog', 350, 'Male', false),
  (2, 'Diva', 'Dog', 150, 'Female', true),
  (3, 'Juda', 'Cat', 200, 'Male', true),
  (4, 'Cleo', 'Cat', 100, 'Female', true),
  (5, 'Jack', 'Bird', 200, 'Male', false);