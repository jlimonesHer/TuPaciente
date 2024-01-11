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

// Verificar si se ha enviado el ID del paciente para eliminar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $idPaciente = $_POST["id"];

    // Obtener la información del archivo a eliminar
    $sqlSelect = "SELECT imagen_perfil FROM pacientes WHERE id = $idPaciente";
    $resultSelect = $conn->query($sqlSelect);

    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $imagenPath = $row["imagen_perfil"];

        // Eliminar la entrada en la base de datos
        $sqlDelete = "DELETE FROM pacientes WHERE id = $idPaciente";

        if ($conn->query($sqlDelete) === TRUE) {
            // Eliminar el archivo de la carpeta "upload"
            if (file_exists($imagenPath)) {
                unlink($imagenPath);
                echo "Paciente eliminado correctamente, y la imagen fue eliminada.";
            } else {
                echo "Paciente eliminado correctamente, pero no se encontró la imagen.";
            }
        } else {
            echo "Error al eliminar el paciente: " . $conn->error;
        }
    } else {
        echo "No se encontró información del paciente con el ID proporcionado.";
    }
} else {
    echo "ID del paciente no proporcionado.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
