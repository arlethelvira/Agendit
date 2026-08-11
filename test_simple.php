<?php
$host = "localhost";
$port = "5432";
$dbname = "DBagendit";
$user = "postgres";
$password = "se1503";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    echo "CONEXION EXITOSA\n";

    $stmt = $conn->query("SELECT COUNT(*) as total FROM usuario");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Usuarios en la tabla: " . $row['total'] . "\n";

} catch (PDOException $e) {
    echo "ERROR DE CONEXION: " . $e->getMessage() . "\n";
}