-- CreaciÃn de la base de datos
drop database if exists tfgdb;
create database tfgdb CHARACTER SET utf8 COLLATE utf8_general_ci;
use tfgdb;

-- CreaciÃ³n del usuario dotandole de permisos
grant usage on *.* to tfguser@localhost identified by 'tfgpass';
grant all privileges on tfgdb.* to tfguser@localhost;
