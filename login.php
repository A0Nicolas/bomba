<?php
//Manejo de la sesion
session_start();
//Si todo estpa bien vamos a redirigir al dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}
$error = '';
//Procesamiento del formulario login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conexion.php';
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    //Buscamos el usuario en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$user]);
    $usuarioDB = $stmt->fetch(PDO::FETCH_ASSOC);

    //Verificamos la contraseña
    if ($usuarioDB && password_verify($pass, $usuarioDB['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $usuarioDB['username'];
        header("Location: index.php");
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login de Administracion</title>
</head>

<body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh">
    <div class="card shadow p-4" style="width:350px">
        <h3 class="text-center mb-4">Acceso Seguro</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="">Usuario</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="">Contraseña</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
    </div>
</body>

</html>