<?php
declare(strict_types=1);

$host = 'mysql';
$dbname = 'sistema_cotizaciones';
$username = 'admin';
$password = 'espe2026';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Crear tablas si no existen
    $pdo->exec("CREATE TABLE IF NOT EXISTS cotizaciones(
        id INT AUTO_INCREMENT PRIMARY KEY,
        componente VARCHAR(100) NOT NULL,
        cantidad INT NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios(
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )");
    
    // Crear usuario admin si no existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('espe2026', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password) VALUES ('admin', ?)");
        $stmt->execute([$hash]);
        error_log("Usuario admin creado con contraseña: espe2026");
    }
    
} catch (PDOException $e) {
    die("Error de conexión MySQL: " . $e->getMessage());
}
?>