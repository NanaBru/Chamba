create table usuario(
    Cedula char(8) primary key,
    nombre varchar(50) NOT NULL,
    apellido varchar(50) NOT NULL,
    edad int(2) NOT NULL CHECK (edad >= 1 AND edad <= 100),
    telefono int(9) NOT NULL,
    rol varchar(50) NOT NULL,
    email varchar(50) UNIQUE NOT NULL,
    password varchar(255) NOT NULL
    
);

CREATE TABLE publicaciones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    proveedor_cedula CHAR(8) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_proveedor_cedula 
        FOREIGN KEY (proveedor_cedula) 
        REFERENCES usuario(Cedula) 
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
