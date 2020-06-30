<?php
session_start();

$logged_in = false;
$disabled = '';
$disabledforc = '';

if (isset($_SESSION['name']) ) {
    $logged_in = true; 
}
else{
    die("ACCESS DENIED");
    header("Location: index.php");
    return;
}

if (isset($_SESSION['type'])) {
    if ($_SESSION['type'] == 'Tenant') {
        $disabled = 'disabled';
        $disabledforc = '';
        die("ACCESS DENIED");
        header("Location: index.php");
        return;
    }
    else {
        $disabledforc = 'disabled';
    }
}

else {
    $disabled = 'disabled';
    $disabledforc = '';
    die("ACCESS DENIED");
    header("Location: index.php");
    return;
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "housing-mate";
$ownerId = $_SESSION['id'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT ahouses.houseNumber, users.userName, ahouses.housePreference FROM ahouses, users, requests WHERE requests.tenantId=users.userId AND requests.houseId=ahouses.houseId AND ahouses.houseOwnerId='.$ownerId.'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo $result['houseNumber'];
    echo $result['userName'];
    echo $result['housePreference'];
} 
catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}

$conn = null;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <title>Housing Mate | Check Book Requests</title>
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
                    <li class="nav-item">
                        <a class="nav-link <?php echo $disabled;?>" href="addflat.php" >Add Flats for Rent</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link <?php echo $disabled;?>" href="#" >Check Book Requests</a>
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
        <div class="container-fluid">
            <?php 
            echo '<table class="table">
            <thead>
              <tr>
                <th scope="col">House Number</th>
                <th scope="col">Customer Name</th>
                <th scope="col">House Preference</th>
              </tr>
            </thead>
            <tbody>';
            while ($row = $stmt->fetch()) {
                echo '<tr>';
                    echo '<td>'.$row['houseNumber'];
                    echo '</td>';
                    echo '<td>'.$row['userName'];
                    echo '</td>';
                    echo '<td>';
                    if($row['housePreference']=='Rent')
                    {
                        echo '&nbsp;<span class="badge badge-success">Rent</span>';
                    }
                    else {
                        echo '&nbsp;<span class="badge badge-danger">Sale</span>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
            echo '</tbody>';
            echo '</table>';

            ?>
        </div>
        <br>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>