<?php
/**
 * Archivo: conexion.php
 * Descripción: Establece la conexión principal con la base de datos MySQL 'db_huerta' utilizando la extensión mysqli.
 * Componente: Base de Datos / Configuración
 */

// Configuración de la base de datos
$servidor = "localhost";
$usuario  = "root";
$password = ""; // En XAMPP por defecto viene vacío
$base_datos = "db_huerta";

// Crear la conexión usando POO
// $conexion = new mysqli($servidor, $usuario, $password, $base_datos);
$conexion = new mysqli("localhost", "root", "", "db_huerta", 3307);

// Verificar si hubo algún error
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configurar el juego de caracteres a UTF-8 (para que se vean bien los acentos)
$conexion->set_charset("utf8");

//echo "¡Conexión exitosa a la huerta de Sandra! 🌱";
?>