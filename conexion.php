<?php
//Conexion hacia PHP
declare(strict_types=1);
try {
    //Conexion hacia la bdd SQLite
    $pdo = new PDO('sqlite:sistema.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Creamos las tablas
    $pdo->exec("CREATE TABLE IF NOT EXISTS cotizaciones(
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        componente TEXT NOT NULL,
        cantidad INTEGER NOT NULL,
        total REAL NOT NULL, 
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    //Tabla para los usuarios (Administrador)
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
    )");

    //La insercion del usuario por defecto si la tabla está vacia
    //Vamos a utilizar password hash
    $stmt = $pdo->query("SELECT COUNT (*) FROM usuarios");
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('espe2026', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO usuarios (username, password) VALUES ('admin', '$hash')");
    }
} catch (PDOException $e) {
    die("Error de conexion: " . $e->getMessage());
}