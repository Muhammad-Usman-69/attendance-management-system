<?php
session_start();

//check if user is logged in
if ($_SESSION["log"] != true) {
    header("location:/log?error=Please log in");
    exit();
}

$mark = $_GET["mark"];

//if mark isn't avaiable
if (!isset($mark)) {
    header("location:/?error=Access Denied");
}

include ("_dbconnect.php");

$id = $_SESSION["id"];

//Get today's date
$date = date("Y-m-d");

//check if already marked
$sql = "SELECT * FROM `attendance` WHERE `date` = ? AND $id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $date, $mark);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$num = mysqli_num_rows($result);
if ($num != 0) {
    header("location: /?error=Already Marked");
    exit();
}

//updating
$sql = "UPDATE `attendance` SET $id = ? WHERE `date` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $mark, $date);
$bool = mysqli_stmt_execute($stmt);
if ($bool) {
    header("location: /?alert=Marked Successfully");
    exit();
}