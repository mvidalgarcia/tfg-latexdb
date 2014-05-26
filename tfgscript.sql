/*** Creating DB. It must be a DB called 'test' previously. ***/

select 'Go to DB "test"' as 'Action';
use test;

select 'Drop DB "tfgdb" if exists' as 'Action';
drop database if exists tfgdb;

select 'Create DB "tfgdb"' as 'Action';
create database tfgdb;

select 'Go to DB "tfgdb"' as 'Action';
use tfgdb;


/*** Simple tables ***/

select 'Create table called "problema"' as 'Action';
create table problema (
	id_problema integer not null auto_increment primary key,
	enunciado_general text,
	resumen text
);
alter table problema auto_increment = 100;

select 'Create table called "pregunta"' as 'Action';
create table pregunta (
	id_pregunta integer not null auto_increment primary key,
	enunciado text not null,
	solucion text,
	explicacion text,
	puntuacion integer not null default 1,
	posicion integer not null,
	id_problema integer not null,
	foreign key (id_problema) references problema(id_problema)
						on delete cascade
						on update cascade
);
alter table pregunta auto_increment = 200;
alter table pregunta add unique index(id_problema, posicion);

select 'Create table called "imagen"' as 'Action';
create table imagen (
	id_imagen integer not null auto_increment primary key,
	url text
);
alter table imagen auto_increment = 400;

select 'Create table called "doc_final"' as 'Action';
create table doc_final (
	id_doc integer not null auto_increment primary key,
	titulacion varchar(80),
	asignatura varchar(80),
	convocatoria varchar(40),
	instrucciones text,
	fecha date,
	estado enum('abierto', 'cerrado', 'publicado') not null
);
alter table doc_final auto_increment = 300;

select 'Create table called "tag"' as 'Action';
create table tag (
	id_tag integer not null auto_increment primary key,
	nombre varchar(40) not null unique
);
alter table tag auto_increment = 500;


/*** Relationship tables ***/

select 'Create table called "problema_doc_final"' as 'Action';
create table problema_doc_final (
	id_problema integer not null,
	id_doc integer not null,
	posicion integer not null,
	primary key (id_problema, id_doc),
	foreign key (id_problema) references problema(id_problema)							
						on delete cascade
						on update cascade,
	foreign key (id_doc) references doc_final(id_doc)
						on delete cascade
						on update cascade
);
alter table problema_doc_final add unique index(id_doc, posicion);

select 'Create table called "problema_tag"' as 'Action';
create table problema_tag (
	id_problema integer not null, 
	id_tag integer not null,
	primary key (id_problema, id_tag),
	foreign key (id_problema) references problema(id_problema)							
						on delete cascade
						on update cascade,
	foreign key (id_tag) references tag(id_tag)							
						on delete cascade
						on update cascade
);

select 'Create table called "problema_imagen"' as 'Action';
create table problema_imagen (
	id_problema integer not null, 
	id_imagen integer not null,
	nombre_amigable varchar(40) not null unique,
	primary key (id_problema, id_imagen),
	foreign key (id_problema) references problema(id_problema)							
						on delete cascade
						on update cascade,
	foreign key (id_imagen) references imagen(id_imagen)							
						on delete cascade
						on update cascade
);


/*** Some example inserts. ***/

select 'Inserting values in "problema" table' as 'Action';
insert into problema (enunciado_general, resumen) values ('Esto es el enunciado general del problema 1', 'Esto es el resumen del problema 1'),
('Esto es el enunciado general del problema 2', 'Esto es el resumen del problema 2'),
('Esto es el enunciado general del problema 3', 'Esto es el resumen del problema 3');

select 'Inserting values in "pregunta" table' as 'Action';
insert into pregunta (enunciado, solucion, explicacion, posicion, id_problema) values 
('Esto es el enunciado de la pregunta 1','Esto es la solución de la pregunta 1', 'Esto es la explicación de la pregunta 1', 1, 100),
('Esto es el enunciado de la pregunta 2','Esto es la solución de la pregunta 2', 'Esto es la explicación de la pregunta 2', 2, 100),
('Esto es el enunciado de la pregunta 3','Esto es la solución de la pregunta 3', 'Esto es la explicación de la pregunta 3', 3, 100),
('Esto es el enunciado de la pregunta 4','Esto es la solución de la pregunta 4', 'Esto es la explicación de la pregunta 4', 1, 101);

select 'Inserting values in "tag" table' as 'Action';
insert into tag (nombre) values ('tagA'),('tagB'),('tagC'),('tagD'),('tagE'),('tagF');

select 'Inserting values in "problema_tag" table' as 'Action';
insert into problema_tag (id_problema, id_tag) values (100, 500), (100, 501), (101, 502), (101, 503), (102, 504), (102, 505);
