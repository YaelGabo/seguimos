<?php
// Iniciar sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datos de conexión a la base de datos
$servername = "localhost:3306";
$username = "root";
$password = "G@bo1007";
$dbname = "herramientas_desarrollo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $_POST['username'] ?? '';
    $contrasena = $_POST['password'] ?? '';

    // Buscar usuario por nombre
    $sql = "SELECT id, contrasena FROM usuarios WHERE nombreUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombreUsuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario, $hashedPassword);
        $stmt->fetch();

        if (password_verify($contrasena, $hashedPassword)) {
            // ✅ Iniciar sesión exitosamente
            $_SESSION['id'] = $id_usuario;

            // Redirigir según tipo de usuario
            if (strpos($nombreUsuario, 'U') === 0) {
                header("Location: ../paginas/FarmaTotal.html");
            } elseif (strpos($nombreUsuario, 'A') === 0) {
                header("Location: ../paginas/Medicamentos.html");
            } else {
                header("Location: ../paginas/inicio.html");
            }
            exit();
        } else {
            $error = "Contraseña incorrecta. Inténtelo de nuevo.";
        }
    } else {
        $error = "No estás registrado. Regístrate primero.";
    }

    $stmt->close();
}

$conn->close();
?>

