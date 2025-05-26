<?php

// Parámetros de conexión a la base de datos
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "PRACTICA_INSCRIPCION_CURSO";

// Se crea la instancia de conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

// Se verifica si la conexión fue exitosa
if ($conn->connect_error) {
    // Si ocurre un error, se detiene la ejecución y se muestra el mensaje
    die("Conexión fallida: " . $conn->connect_error);
}
?>
