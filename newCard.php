<?php
include 'databaseInfo.php';

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$keyword = mysqli_real_escape_string($conn, $_REQUEST['keyword']);
$description = mysqli_real_escape_string($conn, $_REQUEST['description']);

$sql = "INSERT INTO notes (keyword, description) VALUES ('$keyword', '$description')";
if(mysqli_query($conn, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}
 
// Close connection--
mysqli_close($conn);
?>


	