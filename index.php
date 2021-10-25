
<?php
// Include config file
require_once "Configuracion/configuracion.php";

// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a username.";
    } elseif(!preg_match('/^\S+@\S+\.\S+$/', trim($_POST["email"]))){
        $email_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM usuarios WHERE email = ?";

        if($stmt = $mysqli->prepare($sql)){
// Bind variables to the prepared statement as parameters
$stmt->bind_param("s", $param_email);

// Set parameters
$param_email = trim($_POST["email"]);

// Attempt to execute the prepared statement
if($stmt->execute()){
// store result
$stmt->store_result();

if($stmt->num_rows == 1){
$email_err = "This username is already taken.";
} else{
$email = trim($_POST["email"]);
}
} else{
echo "Oops! Something went wrong. Please try again later.";
}

// Close statement
$stmt->close();
}
}

// Validate password
if(empty(trim($_POST["password"]))){
$password_err = "Please enter a password.";
} elseif(strlen(trim($_POST["password"])) < 6){
$password_err = "Password must have atleast 6 characters.";
} else{
$password = trim($_POST["password"]);
}

// Validate confirm password
if(empty(trim($_POST["confirm_password"]))){
$confirm_password_err = "Please confirm password.";
} else{
$confirm_password = trim($_POST["confirm_password"]);
if(empty($password_err) && ($password != $confirm_password)){
$confirm_password_err = "Password did not match.";
}
}

// Check input errors before inserting in database
if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){

// Prepare an insert statement
$sql = "INSERT INTO usuarios (email, passwd) VALUES (?, ?)";

if($stmt = $mysqli->prepare($sql)){
// Bind variables to the prepared statement as parameters
$stmt->bind_param("ss", $param_email, $param_password);

// Set parameters
$param_email = $email;
$param_password = password_hash($password, PASSWORD_BCRYPT); // Creates a password hash

// Attempt to execute the prepared statement
if($stmt->execute()){
// Redirect to login page
header("location: login.php");
} else{
echo "Oops! Something went wrong. Please try again later.";
}

// Close statement
$stmt->close();
}
}

// Close connection
$mysqli->close();
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Registro</title>

      <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    

    <!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
      <link href="../css/signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    
<main class="form-signin">
  <form action="<?php echo htmlspecialchars($_SERVER["SCRIPT_NAME"]); ?>" method="post">
    <img class="mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="form-floating">
      <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" id="floatingInput">
      <label for="floatingInput">Email address</label>
        <span class="invalid-feedback"><?php echo $email_err; ?></span>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" id="floatingPassword">
      <label for="floatingPassword">Password</label>
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </div>
      <div class="form-floating">
          <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
          <label for="floatingPassword">Confirm password</label>
          <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
      </div>

    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me"> Remember me
      </label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
      <p>Already have an account? <a href="login.php">Login here</a>.</p>
  </form>
</main>


    
  </body>
</html>
