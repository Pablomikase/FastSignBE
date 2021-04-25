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
$name = $direction = $cif= $state = $area = "";
$name_err = $direction_err = $cif_err = $state_err = $area_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //Validate name
    if(empty(trim($_POST["company_name"]))){
        $name_err = "Porfavor, ingrese el nombre de la empresa a registrar";
    }else{
        $name = trim($_POST["company_name"]);
    }

    //Validate CIF
    if (empty($_POST["company_cif"])) {
        $cif_err = "Porfavor, ingrese un CIF";
    }else if(strlen(trim($_POST["company_cif"]))!=9){
        $cif_err = "El cif ingresado tiene que tener 9 caracteres.";
    }else{
        $cif = trim($_POST["company_cif"]);
    }

    //Validate surname
    if(empty(trim($_POST["company_direction"]))){
        $direction_err = "Porfavor, ingrese una dirección";
    }else{
        $direction = trim($_POST["company_direction"]);
    }


    //Validate username
    if(empty(trim($_POST["company_state"]))){
        $state_err = "Porfavor, ingrese una dirección";
    }else{
        $state = trim($_POST["company_state"]);
    }

    // Validate new password
    if(empty(trim($_POST["company_area"]))){
        $area_err = "Porfavor, ingres el área de trabajo de la empresa.";
    }else{
        $area = trim($_POST["company_area"]);
    }


    // Check input errors before updating the database
    if(empty($name_err) && empty($cif_err) && empty($direction_err) && empty($state_err) && empty($area_err)){
        // Prepare an update statement

        $sql = "INSERT INTO company (companyName, cifNumber, companyState, companyDirection, companyArea) VALUES (?, ?, ?, ?,?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_cif, $param_state, $param_direction, $param_area);

            // Set parameters
            $param_name = $name;
            $param_cif = $cif;
            $param_state = $state;
            $param_direction = $direction;
            $param_area = $area;

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
    <h2>Creación de Empresas</h2>
    <p>Rellene los datos de la empresa a crear.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Nombre de la Empresa</label>
            <input type="text" name="company_name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
            <span class="invalid-feedback"><?php echo $name_err; ?></span>
        </div>

        <div class="form-group">
            <label>CIF</label>
            <input type="text" name="company_cif" class="form-control <?php echo (!empty($cif_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cif; ?>">
            <span class="invalid-feedback"><?php echo $cif_err; ?></span>
        </div>

        <div class="form-group">
            <label>Dirección de la empresa</label>
            <input type="text" name="company_direction" class="form-control <?php echo (!empty($direction_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $direction; ?>">
            <span class="invalid-feedback"><?php echo $direction_err; ?></span>
        </div>

        <div class="form-group">
            <label>Provincia</label>
            <input type="text" name="company_state" class="form-control <?php echo (!empty($state_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $state; ?>">
            <span class="invalid-feedback"><?php echo $state_err; ?></span>
        </div>

        <div class="form-group">
            <label>Area</label>
            <input type="text" name="company_area" class="form-control <?php echo (!empty($area_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $area_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a class="btn btn-link ml-2" href="welcomeSuperAdmin.php.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>