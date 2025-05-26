<?php
include "conexion.php";
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: cursos.php");
    exit();
}

$id_curso = intval($_GET['id']);

// Verificar cupos disponibles
$sql = "SELECT nombre, CUPOS FROM cursos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_curso);
$stmt->execute();
$stmt->bind_result($nombre_curso, $cupos);
$stmt->fetch();
$stmt->close();

if ($cupos <= 0) {
    echo "<p style='color:red;'>No hay cupos disponibles para este curso.</p>";
    echo "<a href='cursos.php'>Volver a cursos</a>";
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscribirse en <?php echo htmlspecialchars($nombre_curso); ?></title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Formulario de Inscripción</h1>
    <form action="procesar_inscripcion.php" method="POST">
        <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br>
        <label>Correo:</label><br>
        <input type="email" name="correo" required><br>
        <label>Teléfono:</label><br>
        <input type="text" name="telefono"><br><br>
        <input type="submit" value="Inscribirse">
    </form>
    <a href="cursos.php" class="panel-btn">Volver a Cursos</a>
    <a href="logout.php" class="logout-btn">Cerrar sesión</a>
<footer class="footer-app">
    Desarrollado por Rolando Castillo - práctica UTPL 2025
</footer>
</body>
</html>
