<?php
session_start();
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $stmt = $conn->prepare("SELECT contrasena FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($hash_guardado);

    if ($stmt->fetch()) {
        if (password_verify($contrasena, $hash_guardado)) {
            // Login exitoso
            $_SESSION['usuario'] = $usuario;
            header("Location: cursos.php");
            exit();
        } else {
            echo "<p style='color:red;'>Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p style='color:red;'>Usuario no encontrado.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.html");
    exit();
}
?>