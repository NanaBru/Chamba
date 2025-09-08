create table usuario(
    Cedula char(8) primary key,
    nombre varchar(50) NOT NULL,
    apellido varchar(50) NOT NULL,
    edad int(2) NOT NULL CHECK (edad >= 1 AND edad <= 100),
    telefono int(9) NOT NULL,
    email varchar(50) UNIQUE NOT NULL,
    password varchar(255) NOT NULL
    
);


