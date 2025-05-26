<?php
// Inicia o reanuda la sesión del usuario.
// Esto permite verificar si el usuario está autenticado antes de mostrar la página.
session_start();

// Verifica si existe una sesión activa para el usuario.
// Si no hay sesión, redirige al usuario a la página de inicio de sesión (index.html).
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripción Exitosa</title>
    <!-- Se enlaza la hoja de estilos principal para mantener la coherencia visual del sitio -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <!-- 
        Bloque principal de la página de confirmación.
        Muestra un mensaje de agradecimiento y confirmación al usuario tras inscribirse exitosamente.
        Incluye un enlace para regresar al inicio de la aplicación.
        El footer proporciona información de autoría y contexto académico.
    -->
    <h1>¡Gracias por inscribirte!</h1>
    <p>Tu inscripción se ha registrado correctamente.</p>
    <a href="index.html" class="panel-btn">Volver al inicio</a>
    <footer class="footer-app">
        Desarrollado por Rolando Castillo - práctica UTPL 2025
    </footer>
</body>
</html>
