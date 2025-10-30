CREATE DATABASE IF NOT EXISTS chambaBD
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE chambaBD;


CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    apellido VARCHAR(100),
    edad INT,
    telefono VARCHAR(20) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    foto_perfil VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_modificasion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    
);

CREATE TABLE publicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255),       -- ruta/nombre del archivo de imagen
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00, -- precio en pesos
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE calificasion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    publicacion_id INT NOT NULL,
    usuario_id INT NOT NULL,
    estrellas TINYINT NOT NULL CHECK (estrellas BETWEEN 1 AND 5),
    comentario TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (publicacion_id) REFERENCES publicaciones(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);



CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emisor_id INT NOT NULL,
    receptor_id INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (emisor_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (receptor_id) REFERENCES usuario(id) ON DELETE CASCADE
);


/* CREATE DATABASE IF NOT EXISTS chambaBD
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE chambaBD;

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(100) NOT NULL,
    apellido   VARCHAR(100) NOT NULL,
    edad       INT,
    telefono   VARCHAR(20) UNIQUE,
    email      VARCHAR(100) UNIQUE,
    password   VARCHAR(255) NOT NULL
);

CREATE TABLE publicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo     VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen     VARCHAR(255),                  -- ruta o nombre del archivo de imagen
    precio     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    fecha      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
); */
