create table users(
    id int auto_increment primary key,
    nombre varchar(100) unique,
    email varchar(100) unique,
    pass varchar(256),
    logo varchar(250) default "./img/default.png"
);
create table posts(
   id int auto_increment primary key,
   titulo varchar(120) not null,
   contenido text,
   estado  enum("Publicado", "Borrador") default "Borrador",
   fecha timestamp DEFAULT CURRENT_TIMESTAMP,
   user_id int,
   constraint users_posts foreign key(user_id) references users(id) 
   on delete cascade on update cascade 
);