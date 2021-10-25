<?php
// Initialize the session

 
// Check if the user is logged in, otherwise redirect to login page

 
// Include config file
require_once "Configuracion/configuracion.php";

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("select * from usuarios where email = ?");
    $stmt->bind_param('s', $email);
    if($stmt->execute()){
        $result = $stmt->get_result();
            $stmt = $mysqli->prepare("INSERT INTO usuarios (email, passwd) VALUES (?,?)");
            $stmt->bind_param('ss', $email, $password);
            $stmt->execute();
            $stmt->close();
            $mysqli->close();

    }

}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        button{
            background-color:white; border-color:white; color:white
        }
    </style>
</head>
<body>
<div class="contenedor">
    <h1 id="titulo">Restablece la contraseña</h1>
    <div class="wrapper">
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input required type="email" class="form-control" name="email">
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input required type="password" class="form-control" name="password">
            </div>
            <div class="form-group">
                <button name="submit" type="submit"><a href="login.php">Cambiar contraseña</a></button>
            </div>
        </form>
    </div>
</div>
</body>
</html>