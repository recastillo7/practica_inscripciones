<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}
include "conexion.php";

// Simula datos de sesión (ajusta según tu sistema de login)
$usuario = $_SESSION['usuario'];

// Obtén datos del usuario (ajusta según tu estructura de usuarios)
$sql_usuario = "SELECT nombre, correo FROM usuarios WHERE usuario = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $usuario);
$stmt_usuario->execute();
$stmt_usuario->bind_result($nombre, $correo);
$stmt_usuario->fetch();
$stmt_usuario->close();

// Cursos inscritos
$sql_inscritos = "SELECT c.nombre, c.descripcion, c.fecha_inicio 
                  FROM cursos c
                  INNER JOIN inscritos i ON c.id = i.id_curso
                  WHERE i.correo = ?";
$stmt_inscritos = $conn->prepare($sql_inscritos);
$stmt_inscritos->bind_param("s", $correo);
$stmt_inscritos->execute();
$result_inscritos = $stmt_inscritos->get_result();

// Cursos ofertados (no inscritos)
$sql_ofertados = "SELECT * FROM cursos WHERE id NOT IN (
                    SELECT id_curso FROM inscritos WHERE correo = ?
                  )";
$stmt_ofertados = $conn->prepare($sql_ofertados);
$stmt_ofertados->bind_param("s", $correo);
$stmt_ofertados->execute();
$result_ofertados = $stmt_ofertados->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <a href="logout.php" class="logout-btn">Cerrar sesión</a>
    <a href="cursos.php" class="panel-btn">Volver a Cursos</a>
    <div class="panel-container">
        <h1>Panel de Usuario</h1>
        <section class="perfil">
            <h2>Perfil</h2>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></p>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($correo); ?></p>
        </section>
        <section class="inscritos">
            <h2>Cursos Inscritos</h2>
            <?php if ($result_inscritos->num_rows > 0): ?>
                <ul>
                <?php while ($curso = $result_inscritos->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($curso['nombre']); ?></strong><br>
                        <?php echo htmlspecialchars($curso['descripcion']); ?><br>
                        Inicio: <?php echo htmlspecialchars($curso['fecha_inicio']); ?>
                    </li>
                <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No estás inscrito en ningún curso.</p>
            <?php endif; ?>
        </section>
        <section class="ofertados">
            <h2>Cursos Ofertados</h2>
            <?php if ($result_ofertados->num_rows > 0): ?>
                <ul>
                <?php while ($curso = $result_ofertados->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($curso['nombre']); ?></strong><br>
                        <?php echo htmlspecialchars($curso['descripcion']); ?><br>
                        Inicio: <?php echo htmlspecialchars($curso['fecha_inicio']); ?><br>
                        <a href="inscripcion.php?id=<?php echo $curso['id']; ?>">Inscribirse</a>
                    </li>
                <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No hay cursos ofertados disponibles.</p>
            <?php endif; ?>
        </section>
    </div>
    <footer class="footer-app">
        Desarrollado por Rolando Castillo - práctica UTPL 2025
    </footer>
</body>
</html>
<?php
$stmt_inscritos->close();
$stmt_ofertados->close();
$conn->close();
?>