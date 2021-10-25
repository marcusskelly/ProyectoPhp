<?php

$autenticado = false;
// Initialize the session


// Include config file
require_once "Configuracion/configuracion.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, email, passwd FROM usuarios WHERE email = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $result = $stmt->get_result();

                // Check if username exists, if yes then verify password
                if($result->num_rows == 1){
                    // Bind result variables
                    $fila = $result->fetch_assoc();
                        if(password_verify($password, $fila["passwd"])){
                            // Password is correct, so start a new session

                            $autenticado=true;
                            if(isset($_POST["recordar"])){

                                setcookie("nombre_usuario", $_POST["email"],time()+86400);
                            }

                            // Redirect user to welcome page
                            header("location: calendario.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "La constraseña no es correcta.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "La contraseña o nombre no son correctos.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px;
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
            border: solid;
            background: seagreen;
            box-shadow: 10px 10px 5px #999;
        }

        .contenedor{
            width: 100%;
            height: 100%;
        }

        #titulo{
            text-align: center;
            width: 50%;
            justify-content: center;
            margin-left: 25%;
            margin-right: 25%;
            margin-top: 5%;
            font-family: "Calibri Light";
            font-weight: bold;

        }
        body{
            background-color: gray;
        }
    </style>
</head>
<body>
<div class="contenedor">
    <h1 id="titulo">Login</h1>
<div class="wrapper">
    <p>Inicia sesion para reservar pistas</p>

    <?php
    if(!empty($login_err)){
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["SCRIPT_NAME"]); ?>" method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login" >
        </div>
        <div class="form-group">
            <p>Marca la casilla para recordar usuario</p>
            <input type="checkbox" class="btn btn-primary" name="recordar" id="recordar" >
        </div>
        <p>Todavía no tienes cuenta? <a href="registro.php">Apuntate ya</a>.</p>
        <p>Has olvidado tu contraseña? <a href="reset-password.php">Crea una nueva contraseña</a>.</p>
    </form>
</div>
</div>
</body>
</html>