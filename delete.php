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

// Get the ID of the record to delete
$id = $_GET['id'];

// Retrieve the image link from the database
$query = "SELECT img_link FROM result WHERE id = $id";
$result = mysqli_query($cnx, $query);
$row = mysqli_fetch_assoc($result);
$imgLink = $row['img_link'];

// Delete the record from the database
$query = "DELETE FROM result WHERE id = $id";
$deleteResult = mysqli_query($cnx, $query);

if ($deleteResult) {
    // Close the database connection
    mysqli_close($cnx);

    // Delete the associated image file
    if (unlink($imgLink)) {
        // Image file deleted successfully
        echo "Record and associated image deleted successfully";
    } else {
        echo "Error deleting the associated image file";
    }

    // Redirect to result.php
    header('Location: result.php');
    exit;
} else {
    echo "Error deleting the record: " . mysqli_error($cnx);
}

// Close the database connection
mysqli_close($cnx);
?>
