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
$username = $password = $dni= $name = $surname = "";
$username_err = $password_err = $dni_err = $name_err = $surname_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //Validate name
    if(empty(trim($_POST["admin_name"]))){
        $name_err = "Porfavor, ingrese un nombre";
    }else{
        $name = trim($_POST["admin_name"]);
    }
    //Validate surname
    if(empty(trim($_POST["admin_surname"]))){
        $surname_err = "Porfavor, ingrese un apellido";
    }else{
        $surname = trim($_POST["admin_surname"]);
    }

    //Validate DNI
    if (empty($_POST["admin_dni"])) {
        $dni_err = "Porfavor, ingrese un DNI";
    }else if(strlen(trim($_POST["admin_dni"]))!=9){
        $dni_err = "El dni ingresado tiene que tener 9 caracteres.";
    }else{
        $dni = trim($_POST["admin_dni"]);
    }

    //Validate username
    if(empty(trim($_POST["admin_username"]))){
        $username_err = "Porfavor, ingrese un nombre de usuario válido";
    }else{
        $username = trim($_POST["admin_username"]);
    }

    // Validate new password
    if(empty(trim($_POST["admin_password"]))){
        $password_err = "Porfavor, ingrese un nuevo password";
    } elseif(strlen(trim($_POST["admin_password"])) < 6){
        $password_err = "Su passsword tiene que tener al menos 6 caracteres.";
    } else{
        $password = trim($_POST["admin_password"]);
    }


    // Check input errors before updating the database
    if(empty($username_err) && empty($password_err) && empty($dni_err) && empty($name_err) && empty($surname_err)){
        // Prepare an update statement

        $sql = "INSERT INTO admin (username, password, dni, firstname, surName) VALUES (?, ?, ?, ?,?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_password, $param_dni, $param_name, $param_surname);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_dni = $dni;
            $param_name = $name;
            $param_surname = $surname;

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
    <h2>Creación de Administradores</h2>
    <p>Rellene los datos del administrador a crear.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="admin_username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $name_err; ?></span>
        </div>

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="admin_dni" class="form-control <?php echo (!empty($dni_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dni; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="admin_name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
            <span class="invalid-feedback"><?php echo $name_err; ?></span>
        </div>

        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" name="admin_surname" class="form-control <?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $surname; ?>">
            <span class="invalid-feedback"><?php echo $surname_err; ?></span>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="admin_password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a class="btn btn-link ml-2" href="welcomeSuperAdmin.php.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
