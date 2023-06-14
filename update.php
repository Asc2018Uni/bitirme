<?php
// Establish a connection to the MySQL database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'project';

$cnx = mysqli_connect($host, $user, $password, $database);

if (!$cnx) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the form data
$explain = $_POST['explain'];
$date = $_POST['date'];
$time = $_POST['time'];
$payment = $_POST['payment'];
$total = $_POST['total'];

// Update the data in the database
$query = "UPDATE result SET aciklama='$explain', tarih='$date', saat='$time', odeme='$payment', toplam='$total' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($cnx, $query);

if ($result) {
    // Close the database connection
    mysqli_close($cnx);

    // Redirect to result.php
    header('Location: result.php');
    exit;
} else {
    echo "Error updating data: " . mysqli_error($cnx);
}

// Close the database connection
mysqli_close($cnx);
