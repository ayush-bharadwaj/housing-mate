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

if (isset($_POST['userName']) && isset($_POST['email']) && isset($_POST['password'])) 
{
    if (strlen($_POST['userName']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1 ) 
    {
        $_SESSION['failure'] = "User name, email and password are required";
        header("Location: signupowner.php");
        return;
    } 
    $pass = htmlentities($_POST['password']);
    $user = htmlentities($_POST['userName']);
    $email = htmlentities($_POST['email']);
    $type = 'Owner';
    
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "housing-mate";  
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO users (userName, userType, userEmail, userPassword)
                VALUES (?, ?, ?, ?)";
        $stmt= $conn->prepare($sql);
        $stmt->execute([$user, $type, $email, $pass]);
        echo "New record created successfully";
    } 
    catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
    error_log("New record created successfully ".$email);
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $user;
    $_SESSION['type'] = 'Owner';
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->query("SELECT userId FROM `users` WHERE userEmail='".$email."'");
        $res = $stmt->fetch();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    error_log($res);
    $_SESSION['id'] = $res['userId'];
    header("Location: index.php");
    return;
}
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <title>Housing Mate | Signup as Owner</title>
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
                    <li class="nav-item">
                        <a class="btn btn-outline-success" href="login.php" role="button">Login</a>
                    </li>
                    &nbsp;
                </ul>
            </div>
        </nav>
        <br>
        <div class="container-fluid ">
            <div class ="card">
                <div class="container-fluid">
                    <h4>Sign-Up as a Owner</h4>
                    <h6 class="text-muted">Tenants and Owners have different Sign-Up pages</h6>
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
                            <label for="userName">User Name</label>
                            <input type="text" class="form-control" name="userName" id="userName">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <input class="btn btn-primary" type="submit" value="Log In">
                        <input class="btn" type="submit" name="logout" value="Cancel">
                    </form>
                    <h6 class="text-muted">New to Housing Mate but not a Owner, 
                        <a class="btn btn-outline-dark" href="signuptenant.php" role="button">Signup as Tenant</a>
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