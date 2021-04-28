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
$adminUsername = $companyName = $adminID = "";
$adminUsername_err = $companyName_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //Validate admin username



    if(empty(trim($_POST["admin_username"]))){
        $adminUsername_err = "Porfavor, ingrese el nombre de la empresa a registrar";
    }else{
        $adminUsername = trim($_POST["admin_username"]);
    }

    //Validate CIF
    if (empty($_POST["company_name"])) {
        $companyName_err = "Porfavor, ingrese un CIF";
    }else{
        $companyName = trim($_POST["company_name"]);
    }



    // Check input errors before updating the database
    if(empty($adminUsername_err) && empty($companyName_err)){

        //UPDATE company SET admin_id = (SELECT id FROM admin WHERE username = 'raquelAdmin') WHERE companyName = 'OrdenadoresJuan';

        // Prepare an update statement
            $sql = "UPDATE company SET admin_id = (SELECT id FROM admin WHERE username = ?) WHERE companyName = ?;";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_admin_username, $param_companyName);

            // Set parameters
            $param_admin_username = $adminUsername;
            $param_companyName = $companyName;


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
            <label>Admin username</label>
            <input type="text" name="admin_username" class="form-control <?php echo (!empty($adminUsername_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $adminUsername; ?>">
            <span class="invalid-feedback"><?php echo $adminUsername_err; ?></span>
        </div>

        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="company_name" class="form-control <?php echo (!empty($companyName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $companyName; ?>">
            <span class="invalid-feedback"><?php echo $companyName_err; ?></span>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a class="btn btn-link ml-2" href="welcomeSuperAdmin.php.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>