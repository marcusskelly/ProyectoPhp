CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    passwd VARCHAR(255) NOT NULL,
    fecha_alta DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reservas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date date NOT NULL,
    email varchar(255) NOT NULL,
    passwd varchar(255) NOT NULL
);