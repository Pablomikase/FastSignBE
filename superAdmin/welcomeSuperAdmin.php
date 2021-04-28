<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: superAdminLoginNoHash.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Portal de Superadministradores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Bienvenido al portal de superadministradores de FastSign.</h1>
    <h3>¿Que operación quieres realizar?</h3>
    <p style="margin-top: 15px; margin-bottom: 15px;">
        <div style="margin-top: 15px; margin-bottom: 15px;">
            <a href="create-admin.php" class="btn btn-success ml-3" >Crear Administrador</a>
            <a href="delete-admin.php" class="btn btn-danger ml-3">Eliminar Administrador</a>
        </div>
        <div style="margin-top: 15px; margin-bottom: 15px;">
            <a href="create-company.php" class="btn btn-success ml-3">Registrar Empresa</a>
            <a href="delete-company.php" class="btn btn-danger ml-3">Eliminar Empresa</a>
        </div>
    <div style="margin-top: 15px; margin-bottom: 15px;">
        <a href="assign-company-to-admin.php" class="btn btn-success ml-3">Asignar empresa</a>
    </div style="margin-top: 15px; margin-bottom: 15px;">
        <a href="logout.php" class="btn btn-info ml-3">Cerrar sesión</a>
    </p>
</body>
</html>
