<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definición de la ruta base
if (!defined('BASE_URL')) {
    define('BASE_URL', '/UTA/'); 
}

$host = 'localhost';
$dbname = 'fsxdtjga_UTA';
$username = 'fsxdtjga_katty'; 
$password = ';h;7NNXNO[Jd!R3,';   

try {
    // Configuración de la conexión PDO
    $opciones = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, $opciones);
    
} catch (PDOException $e) {
    // Manejo de errores de conexión
    die("Error crítico: No se pudo conectar a la base de datos. Detalle: " . $e->getMessage());
}
?>