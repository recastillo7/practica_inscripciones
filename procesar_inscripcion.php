<?php
include "conexion.php";

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
$id_curso = $_POST['id_curso'];

// Validar datos (teléfono puede ser vacío)
if (empty($nombre) || empty($correo) || empty($id_curso)) {
    echo "<p style='color:red;'>Nombre, correo y curso son obligatorios.</p>";
    echo "<a href='cursos.php'>Volver a cursos</a>";
    exit();
}

// Verificar si ya está inscrito
$stmt = $conn->prepare("SELECT 1 FROM inscritos WHERE correo = ? AND id_curso = ?");
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("si", $correo, $id_curso);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<p style='color:red;'>Ya estás inscrito en este curso.</p>";
    echo "<a href='cursos.php'>Volver a cursos</a>";
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// Verificar cupos disponibles
$stmt = $conn->prepare("SELECT CUPOS FROM cursos WHERE id = ?");
$stmt->bind_param("i", $id_curso);
$stmt->execute();
$stmt->bind_result($cupos);
$stmt->fetch();
$stmt->close();

if ($cupos <= 0) {
    echo "<p style='color:red;'>No hay cupos disponibles para este curso.</p>";
    echo "<a href='cursos.php'>Volver a cursos</a>";
    $conn->close();
    exit();
}

// Si hay cupos, insertar inscripción
$stmt = $conn->prepare("INSERT INTO inscritos (nombre, correo, telefono, id_curso) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $nombre, $correo, $telefono, $id_curso);

if ($stmt->execute()) {
    // Descontar un cupo
    $stmt_update = $conn->prepare("UPDATE cursos SET CUPOS = CUPOS - 1 WHERE id = ?");
    $stmt_update->bind_param("i", $id_curso);
    $stmt_update->execute();
    $stmt_update->close();

    header("Location: cursos.php?inscrito=ok");
    exit();
} else {
    // Si el error es por duplicidad
    if ($conn->errno == 1062) {
        echo "<p style='color:red;'>Ya estás inscrito en este curso.</p>";
        echo "<a href='cursos.php'>Volver a cursos</a>";
    } else {
        echo "Error al inscribirse: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>
