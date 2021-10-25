<?php

require_once "Configuracion/configuracion.php";

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if(isset($_GET['date'])){
    $date = $_GET['date'];
    $stmt = $mysqli->prepare("select * from reservas where date = ?");
    $stmt->bind_param('s', $date);
    $bookings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
    }
}

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $mysqli->prepare("select * from reservas where date = ?");
    $stmt->bind_param('s', $date);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $msg = "<div class='alert alert-danger'>Reservado</div>";
        }else{
            $stmt = $mysqli->prepare("INSERT INTO reservas (date, email, passwd) VALUES (?,?,?)");
            $stmt->bind_param('sss', $date, $email, $password);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>Reserva realizada</div>";
            $stmt->close();
            $mysqli->close();
        }
    }
}


?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">

    <style>

        .container{
            background-color: gray;
            background: seagreen;
            box-shadow: 10px 10px 5px #999;
            width: 100%;
        }

        button{
            background-color:white; border-color:white; color:white
        }
    </style>
</head>

<body>
<div class="container">
    <h1 class="text-center">Reserva para el dia: <?php echo date('d/m/Y', strtotime($date)); ?></h1><hr>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?php echo(isset($msg))?$msg:""; ?>
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
                    <button name="submit" type="submit"><a href = "login.php">Reserva</a></button>
                </div>
            </form>
        </div>

    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>

</html>
