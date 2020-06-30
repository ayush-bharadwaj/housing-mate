<?php
session_start();

$logged_in = false;
$failure = false;
$disabled = '';

if (isset($_SESSION['name']) ) {
	$logged_in = true;
}

if ( isset($_SESSION['failure']) ) {
    $failure = htmlentities($_SESSION['failure']);
    unset($_SESSION['failure']);
}

if (isset($_SESSION['type'])) {
    if ($_SESSION['type'] == 'Tenant') {
        die("ACCESS DENIED");
        header("Location: index.php");
        return;
    }
}

if (isset($_POST['houseNumber']) && isset($_POST['houseFloor']) && isset($_POST['prefer'])) 
{
    if (strlen($_POST['houseNumber']) < 1 || strlen($_POST['houseFloor']) < 1 || strlen($_POST['prefer']) < 1 ) 
    {
        $_SESSION['failure'] = "House Number, House Floor and Item Preference are required";
        header("Location: addflat.php");
        return;
    } 
    $item = htmlentities($_POST['houseNumber']);
    $desc = htmlentities($_POST['houseFloor']);
    $price = htmlentities($_POST['Price']);
    $prefer = htmlentities($_POST['prefer']);
    $id = htmlentities($_SESSION['id']);
    $available ='yes';
    error_log($_POST['houseNumber']);
    error_log($_POST['houseFloor']);
    error_log($_POST['Price']);
    error_log($_POST['prefer']);
    
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "housing-mate";  
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO ahouses (houseNumber, houseFloor, houseOwnerId, housePreference, Price, avaliable) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt= $conn->prepare($sql);
        $stmt->execute([$item, $desc, $id, $prefer, $price, $available]);
        echo "New record created successfully";
    } 
    catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
    error_log("New record created successfully ".$item);
    header("Location: index.php");
    return;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <title>Housing Mate | Add Flats for Rent</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Housing Mate</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Houses Avaliable<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link <?php echo $disabled;?>" href="#">Add Flats for rent</a>
                    </li>
                    &nbsp;
                    <?php if($logged_in == false){
                    echo '<li class="nav-item">';
                        echo '<a class="btn btn-outline-success" href="login.php" role="button">Login</a>';
                    echo '</li>';}
                    else {
                        echo '<li class="nav-item">';
                            echo '<a class="btn btn-outline-success" href="#" role="button">Logged in as '.$_SESSION['name'].'</a>';
                        echo '</li>';}
                    ?>
                    &nbsp;
                    <?php if($logged_in == false){}
                    else {
                    echo '<li class="nav-item">';
                        echo '<a class="btn btn-outline-danger" href="logout.php" role="button">Logout</a>';
                    echo '</li>';}
                    ?>
                </ul>
            </div>
        </nav>
        <br>
        <div class="container-fluid ">
            <div class ="card">
                <div class="container-fluid">
                    <h4>Add Flats for Rent</h4>
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
                            <label for="houseNumber">Flat Number</label>
                            <input type="text" class="form-control" name="houseNumber" id="houseNumber">
                        </div>
                        <div class="form-group">
                            <label for="houseFloor">Flat Floor</label>
                            <input type="text" class="form-control" name="houseFloor" id="houseFloor">
                        </div>
                        <div class="form-group">
                            <label for="Price">Price</label>
                            <input type="text" class="form-control" name="Price" id="Price">
                        </div>
                        <div class="Form-group">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-success">
                                    <input type="radio" name="prefer" id="prefer1" value="Rent" checked>Rent
                                </label>
                                <label class="btn btn-outline-danger">
                                    <input type="radio" name="prefer" id="prefer2" value="Sale">Sale
                                </label>
                            </div>
                        </div>
                        <br>
                        <input class="btn btn-primary" type="submit" value="Add">
                        <input class="btn" type="submit" name="logout" value="Cancel">
                    </form>
                </div>
            </div>
        </div>
        <br>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>