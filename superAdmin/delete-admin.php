<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: superAdminLoginNoHash.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$adminUsername = $adminDni ="";
$adminUsername_err = $adminDni_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //Validate company name
    if(empty(trim($_POST["admin_username"]))){
        $adminUsername_err = "Porfavor, ingrese un nombre";
    }else{
        $adminUsername = trim($_POST["admin_username"]);
    }

    //Validate CIF
    if (empty($_POST["admin_dni"])) {
        $adminDni_err = "Porfavor, ingrese un CIF";
    }else if(strlen(trim($_POST["admin_dni"]))!=9){
        $adminDni_err = "El CIF ingresado tiene que tener 9 caracteres.";
    }else{
        $adminDni = trim($_POST["admin_dni"]);
    }




    // Check input errors before updating the database
    if(empty($adminUsername_err) && empty($adminDni_err)){
        // Prepare an delete statement

        $sql = "DELETE FROM admin WHERE username = ? AND dni = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_dni);

            // Set parameters
            $param_username = $adminUsername;
            $param_dni = $adminDni;


            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: welcomeSuperAdmin.php");
            } else{
                echo "Oops! Algo no ha ido bien, verifique que los datos son correctos.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Eliminar Administrador de Empresa</h2>
    <p>Rellene los datos del administrador que quiere eliminar</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username del administrador</label>
            <input type="text" name="admin_username" class="form-control <?php echo (!empty($adminUsername)) ? 'is-invalid' : ''; ?>" value="<?php echo $adminUsername; ?>">
            <span class="invalid-feedback"><?php echo $adminUsername_err; ?></span>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="admin_dni" class="form-control <?php echo (!empty($adminDni)) ? 'is-invalid' : ''; ?>" value="<?php echo $adminDni; ?>">
            <span class="invalid-feedback"><?php echo $adminDni_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a class="btn btn-link ml-2" href="welcomeSuperAdmin.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>