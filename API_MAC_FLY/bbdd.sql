CREATE DATABASE IF NOT EXISTS api_mc_fly;
USE api_mc_fly;

CREATE TABLE notas(
id			int(255) auto_increment not null,
autor 		varchar(255),
nota 		text,
favorito 	varchar(255),
CONSTRAINT pk_productos PRIMARY KEY(id)
)ENGINE=InnoDb;