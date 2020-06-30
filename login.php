<?php

session_start();

$failure = false;

if ( isset($_POST['logout'] ) ) {
    header("Location: index.php");
    return;
}

if ( isset($_SESSION['email'])){
    header("Location: index.php");
    return;
}

if ( isset($_SESSION['failure']) ) {
    $failure = htmlentities($_SESSION['failure']);

    unset($_SESSION['failure']);
}

if ( isset($_POST['email']) && isset($_POST['password'])) 
{
    if ( strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1 ) 
    {
        $_SESSION['failure'] = "User name and password are required";
        header("Location: login.php");
        return;
    } 

    $pass = htmlentities($_POST['password']);
    $email = htmlentities($_POST['email']);
    
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "housing-mate";  
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->query("SELECT * FROM `users` WHERE userEmail='".$email."'");
        $res = $stmt->fetch();
        $result = $res['userPassword'];
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;

    if ($pass != $result) 
    {
        error_log("Login fail ".$pass." ". $result." ");
        $_SESSION['failure'] = "Incorrect password";
        header("Location: login.php");
        return;
    }

    error_log("Login success ".$email);
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $res['userName'];
    $_SESSION['type'] = $res['userType'];
    $_SESSION['id'] = $res['userId'];
    error_log($res['userType']);
    header("Location: index.php");
    return;
}
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <title>Housing Mate | Login</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Housing Mate</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Houses Avaliable<span class="sr-only">(current)</span></a>
                    </li>
                    &nbsp;
                </ul>
            </div>
        </nav>
        <br>
        <div class="container-fluid ">
            <div class ="card">
                <div class="container-fluid">
                    <h4>Login</h4>
                    <h6 class="text-muted">Tenants and Owners have mutual login</h6>
                    <?php
                    if ( $failure !== false ) 
                    {
                        echo(
                            '<p style="color: red;">'.
                                htmlentities($failure).
                            "</p>\n"
                        );
                    }
                    ?>
                    <form method="post">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email" id="email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <input class="btn btn-primary" type="submit" value="Log In">
                        <input class="btn" type="submit" name="logout" value="Cancel">
                    </form>
                    <h6 class="text-muted">New to Housing Mate, 
                        <a class="btn btn-outline-dark" href="signupowner.php" role="button">Signup as Owner</a> | 
                        <a class="btn btn-outline-dark" href="signuptenant.php" role="button">Signup as Tenants</a>
                    </h6>
                </div>
            </div>
        </div>
        <br>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>