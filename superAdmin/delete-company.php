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
$companyName = $companyCif ="";
$companyName_err = $companyCif_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //Validate company name
    if(empty(trim($_POST["company_name"]))){
        $companyName_err = "Porfavor, ingrese un nombre";
    }else{
        $companyName = trim($_POST["company_name"]);
    }

    //Validate CIF
    if (empty($_POST["company_cif"])) {
        $companyCif_err = "Porfavor, ingrese un CIF";
    }else if(strlen(trim($_POST["company_cif"]))!=9){
        $companyCif_err = "El CIF ingresado tiene que tener 9 caracteres.";
    }else{
        $companyCif = trim($_POST["company_cif"]);
    }




    // Check input errors before updating the database
    if(empty($companyName_err) && empty($companyCif_err)){
        // Prepare an delete statement

        $sql = "DELETE FROM company WHERE companyName = ? AND cifNumber = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_companyName, $param_cif);

            // Set parameters
            $param_companyName = $companyName;
            $param_cif = $companyCif;


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
    <h2>Eliminar Empresa</h2>
    <p>Rellene los datos de la empresa que quiere eliminar</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Nombre de la empresa</label>
            <input type="text" name="company_name" class="form-control <?php echo (!empty($companyName)) ? 'is-invalid' : ''; ?>" value="<?php echo $companyName; ?>">
            <span class="invalid-feedback"><?php echo $companyName_err; ?></span>
        </div>

        <div class="form-group">
            <label>CIF</label>
            <input type="text" name="company_cif" class="form-control <?php echo (!empty($companyCif)) ? 'is-invalid' : ''; ?>" value="<?php echo $companyCif; ?>">
            <span class="invalid-feedback"><?php echo $companyCif_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a class="btn btn-link ml-2" href="welcomeSuperAdmin.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>