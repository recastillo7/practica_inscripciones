<?php
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $ciudad = $_POST['ciudad'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Puedes agregar validaciones adicionales aquí

    // Hash de la contraseña
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, contrasena, nombre, apellido, correo, ciudad) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $usuario, $hash, $nombre, $apellido, $correo, $ciudad);

    if ($stmt->execute()) {
        echo "<p>Registro exitoso. <a href='index.html'>Iniciar sesión</a></p>";
    } else {
        echo "<p style='color:red;'>Error: El usuario o correo ya existe.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedor">
        <h1>Registro de Usuario</h1>
        <form action="registro.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required><br>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required><br>

            <label for="ciudad">Ciudad:</label>
            <input type="text" id="ciudad" name="ciudad" required><br>

            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required><br>

            <button type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="index.html">Inicia sesión aquí</a></p>
    </div>
</body>
</html>
<?php } ?>