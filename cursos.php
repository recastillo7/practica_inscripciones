<?php
// Inicia o reanuda la sesión del usuario.
// Esto asegura que solo usuarios autenticados puedan acceder a la página de cursos.
session_start();
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión activa, redirige al usuario a la página de inicio de sesión.
    header("Location: index.html");
    exit();
}
?>
<?php 
// Incluye el archivo de conexión a la base de datos.
// Esto permite ejecutar consultas para obtener los cursos disponibles.
include "conexion.php"; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cursos Disponibles</title>
    <!-- Se enlaza la hoja de estilos principal para mantener la coherencia visual del sitio -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <!-- Barra superior con opciones de cerrar sesión y acceso al panel de usuario -->
    <a href="logout.php" class="logout-btn">Cerrar sesión</a>
    <a href="panel.php" class="panel-btn">Mi Panel</a>
    <h1>Cursos Disponibles</h1>

    <!-- Muestra un mensaje de confirmación si la inscripción fue exitosa -->
    <?php if (isset($_GET['inscrito']) && $_GET['inscrito'] === 'ok'): ?>
        <div class="confirmacion-exito">
            ¡Inscripción realizada con éxito!
        </div>
    <?php endif; ?>

    <?php
    // Consulta para obtener todos los cursos disponibles en la base de datos
    $sql = "SELECT * FROM cursos";
    $resultado = $conn->query($sql);

    // Si existen cursos, se muestran en tarjetas individuales
    if ($resultado->num_rows > 0) {
        while ($curso = $resultado->fetch_assoc()) {
            echo "<div class='curso'>";
            // El nombre del curso es un enlace al formulario de inscripción
            echo "<h2><a href='inscripcion.php?id=" . $curso['id'] . "'>" . $curso['nombre'] . "</a></h2>";
            echo "<p>" . $curso['descripcion'] . "</p>";
            echo "<p>Inicio: " . $curso['fecha_inicio'] . "</p>";
            echo "<p>CUPOS: " . $curso['CUPOS'] . "</p>";
            // Botón para inscribirse en el curso
            echo "<a href='inscripcion.php?id=" . $curso['id'] . "'>Inscribirse</a>";
            echo "</div>";
        }
    } else {
        // Si no hay cursos, se muestra un mensaje informativo
        echo "<p>No hay cursos disponibles.</p>";
    }

    // Cierra la conexión a la base de datos
    $conn->close();
    ?>
    <!-- Footer institucional con información de autoría y contexto académico -->
    <footer class="footer-app">
        Desarrollado por Rolando Castillo - práctica UTPL 2025
    </footer>
</body>
</html>
