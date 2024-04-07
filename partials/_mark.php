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

//if admin
if ($_SESSION["status"] == "admin") {
    $id = $_GET["id"];
}

//if admin is changing atendance
if ($_GET["date"] && $_SESSION["status"] == "admin") {
    $date = $_GET["date"];
    $sql = "UPDATE `attendance` SET `$id` = '$mark' WHERE `date` = '$date'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    //redirecting admin
    header("location: /details?id=$id");
    exit();
}

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
    if ($mark == 2 && $_SESSION["status"] == "student") {
        $alert = "Request has already been forwarded to Admins";
        header("location: /?error=$alert");
        exit();
    }
    header("location:/?error=Already Marked");
    exit();
}

//updating
$sql = "UPDATE `attendance` SET $id = ? WHERE `date` = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $mark, $date);
$bool = mysqli_stmt_execute($stmt);
if ($bool) {
    $alert = "Marked Successfully";

    //for student
    if ($mark == 2 && $_SESSION["status"] == "student") {
        header("location: /?alert=Request has been forwarded to Admins");
        exit();
    }

    //for admin
    if ($_SESSION["status"] == "admin") {
        header("location: /admin?alert=$alert&req=on");
        exit();
    }

    //reedirecting for normal
    header("location: /?alert=$alert");
    exit();
}

// 1 = present
// 2 = leave request
// 3 = absent
// 4 = leave