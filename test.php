<?php
//check if email already exists
include ("partials/_dbconnect.php");

$id = "df1kcDSg";
//Get today's date
$date = date("Y-m-d");
//marking current as absent
$sql = "UPDATE `attendance` SET `$id` = '1' WHERE `date` = '$date'";
// $sql = "UPDATE `attendance` SET `$id` = '2' WHERE `date` = '2024-04-02'";
$stmt = mysqli_prepare($conn, $sql);
$result = mysqli_stmt_execute($stmt);
print_r($result);