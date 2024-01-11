<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión a la base de datos
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y validar los datos del formulario
    $nombre = htmlspecialchars($_POST["nombre"]);
    $animal = htmlspecialchars($_POST["animal"]);
    $edad = intval($_POST["edad"]);
    $telefono = htmlspecialchars($_POST["telefono"]);
    $fecha_visita = htmlspecialchars($_POST["fecha_visita"]);
    $diagnostico = htmlspecialchars($_POST["diagnostico"]);

    // Verificar si se ha cargado un archivo de imagen
    if (isset($_FILES["imagen_perfil"]) && $_FILES["imagen_perfil"]["error"] == 0) {
        // Procesar la imagen de perfil
        $imagen_perfil = $_FILES["imagen_perfil"];
        $nombre_imagen = $imagen_perfil["name"];
        $ruta_imagen = "uploads/" . $nombre_imagen;

        move_uploaded_file($imagen_perfil["tmp_name"], $ruta_imagen);
    } else {
        // Si no se carga una imagen, asignar un valor predeterminado o manejar según sea necesario
        $ruta_imagen = null;
    }

    // Insertar los datos en la base de datos utilizando una consulta preparada
    $sql = $conn->prepare("INSERT INTO pacientes (nombre, animal, edad, telefono, fecha_visita, diagnostico, imagen_perfil) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssissss", $nombre, $animal, $edad, $telefono, $fecha_visita, $diagnostico, $ruta_imagen);

    if ($sql->execute()) {
        echo "Paciente registrado correctamente.";
    } else {
        echo "Error al registrar el paciente: " . $sql->error;
    }

    $sql->close(); // Cerrar la consulta preparada
} else {
    // Redirigir si se intenta acceder directamente a este archivo
    header("Location: index.html");
    exit();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
