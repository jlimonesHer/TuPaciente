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

// Obtener los datos de los pacientes
$sql = "SELECT * FROM pacientes";
$result = $conn->query($sql);

// Manejar posibles errores en la consulta SQL
if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}

// Configurar encabezados de respuesta
header('Content-Type: application/json');

if ($result->num_rows > 0) {
    $pacientes = array();

    while ($row = $result->fetch_assoc()) {
        $pacientes[] = $row;
    }

    // Convertir el array a formato JSON y enviarlo como respuesta
    $json_data = json_encode($pacientes);

    // Manejar posibles errores en la conversión a JSON
    if ($json_data === false) {
        die("Error al convertir datos a JSON: " . json_last_error_msg());
    }

    echo $json_data;
} else {
    // Si no hay pacientes, enviar un array vacío en formato JSON
    echo json_encode([]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
