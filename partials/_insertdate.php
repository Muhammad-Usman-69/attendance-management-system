<?php
//Get today's date
$date = date("Y-m-d");

include ("_dbconnect.php");

//check if date is already inserted
$sql = "SELECT * FROM `attendance` WHERE `date` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $date);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);
if ($num != 0) {
    echo "already inserted";
    exit();
} 

//inserting date into column
$sql = "INSERT INTO `attendance` (`date`) VALUES (?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $date);
$bool = mysqli_stmt_execute($stmt);
echo "inserted successfully";